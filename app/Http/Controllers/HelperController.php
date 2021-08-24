<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Company;
use App\Models\Industry;
use App\Models\Jobtype;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;

class HelperController extends Controller
{
    static function getUserRole()
    {

        if (Auth::check()) {

            $user = User::with('roles')->find(auth()->user()->id);

            foreach ($user->roles as $role) {
                $user_role = $role->name;
            }

            if (!empty($user_role) && $user_role) {
                return $user_role;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /*
    / This Creates a new company if the company has not been found
    / Recommend use for Job posting by machine
   */

    static function getCompanyIdByName($name, $new_company = false, $city = null, $website = null, $email = null, $phone = null, $description = null)
    {
        //TRIM $name of empty spaces from both sides
        $name = trim($name);

        if ($name && strlen($name) > 0 && $name !== ' ') {
            $company  = Company::where('name', '=', $name)->first();
            if ($company && (int) $company->id) {
                return $company->id;
            } else {

                if ($new_company) {
                    //Create New Company using just name then return company id

                    $company = new Company;
                    $company->name = $name;
                    $company->user_id = 1;
                    $company->createdby = 1;
                    $company->points = 5;
                    $company->slug  = HelperController::generateslug('companies', "slug", $name);
                    $company->uuid = HelperController::generateUniquePublicId('companies', 'uuid');
                    $company->city_id = HelperController::getCityIdByName($city) ?? 1;
                    $company->website = HelperController::validateUrl($website);
                    $company->email = HelperController::validateEmail($email);
                    $company->telephone = HelperController::cleanPhoneNumber($phone);
                    $company->description = HelperController::validateDescription($description);
                    $company->save();
                    return $company->id;
                } else {
                    return null;
                }
            }
        }
        return null;
    }

    static function cleanPhoneNumber($phone)
    {
        if (!$phone) {
            return null;
        }

        //explode phone number into strings
        $phones = explode(',', $phone);

        if ($phones && count($phones) > 0) {
            foreach ($phones as $key => $value) {
                $phones[$key] = preg_replace('/[^0-9 +()]/s', '', $value);
            }
            var_dump($phones);
            return json_encode($phones);
        }
        return null;
    }

    static function generateUniquePublicId($tableName, $column = "publicid")
    {

        /*
        * The function expects a model to work with and the name of the column 
        *in the table as a safe-guard. 
        */

        $publicid = Str::uuid()->toString();;

        //Just do the query how ever you want, this is totally generic
        while (DB::table($tableName)->where($column, '=', $publicid)->first()) {

            $publicid = Str::uuid()->toString();
        }

        return $publicid;
    }

    static function generateslug($tableName, $column, $string)
    {
        //Remove special characters
        $clean_string = preg_replace("/[^a-zA-Z0-9_-]/", '-', $string);
        //ise this to check later
        $clean_string = filter_var($clean_string, FILTER_SANITIZE_URL);
        //Remove any special characts, only allow numbers and letters
        $slug = Str::slug($clean_string, '-');
        //Just do the query how ever you want, this is totally generic
        $index = 1;
        while (DB::table($tableName)->where($column, '=', $slug)->first()) {
            $slug = Str::slug($clean_string, '-');
            $slug = $slug . '_' . $index;
            $index++;
        }

        return $slug;
    }

    static public function getCityId($city = "Lusaka")
    {
        if (!$city) {
            return null;
        }

        $get_city = City::where("name", "=", $city)->first();

        if ($get_city) {
            return $get_city->id;
        } else {
            return null;
        }
    }

    static public function getCityIdByName($city = "Lusaka")
    {
        if (!$city) {
            return null;
        }

        $get_city = City::where("name", "=", $city)->first();

        if ($get_city) {
            return $get_city->id;
        } else {
            return null;
        }
    }

    static public function getIndustryId($industry = "Lusaka")
    {
        $get_industry = Industry::where("name", "=", $industry)->first();
        if ($get_industry != null) {
            return $get_industry->id;
        } else {
            return null;
        }
    }

    static public function getIndustryIdByName($industry = "Engineering")
    {
        if (!$industry) {
            return null;
        }

        $get_industry = Industry::where("name", "=", $industry)->first();

        if ($get_industry) {
            return $get_industry->id;
        } else {
            return null;
        }
    }
    //jobtype_id
    static public function getJobtypeIdByName($job_type)
    {
        if (!$job_type) {
            return null;
        }


        $job_types = Jobtype::all()->toArray();
        foreach ($job_types as $item) {
            if ($job_type == $item['name']) {
                return $item['id'];
            }
        }

        return null;
    }

    public function stringContains($string, $array)
    {
        foreach ($array as $el) {
            if (strpos($string, $el) === 0) {
                return true;
            }
        }
        return false;
    }

    static public function validateUrl($url) //Return valid url
    {
        //if the last char is a dot, remove it
        if (substr($url, -1) == ".") {
            $url = substr($url, 0, -1);
        }

        // Remove all illegal characters from a url
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $bad_string = false;

        $array = ['Click here', 'Clickhere', ' Clickhere', 'public/view-profile', 'notset', 'jobs.neebak.xyz', 'neebak.xyz'];
        foreach ($array as $el) {
            if (strpos($url, $el) !== false) {
                $bad_string = true;
                break;
            }
        }

        if ($url && !$bad_string) {
            //Add http:// if it is not included in the url
            $https = strpos($url, 'https://') === 0;

            $http = strpos($url, 'http://') === 0;

            if ($https || $http) {
                return $url;
            } else {
                //They are both false at this point
                $url = 'https://' . $url;
                return $url;
            }
            return $url;
        } else {
            return null;
        }
    }

    static public function purgetags($string)
    {
        if ($string == "not set") {
            return null;
        }
        //remove bad words from the tag string

        $string_array = explode(',', $string);
        $results = [];
        foreach ($string_array as $el) {
            if (strpos($el, 'none listed') || strpos($el, 'unknown') || strpos($el, 'Click here') ||  strpos($el, ' Click here') ||  strpos($el, 'Clickhere') || strpos($el, 'not set')) continue;
            $results[] = $el;
        }
        //lower  string to lowercase
        return json_encode($results);
    }


    static public function validateEmail($email)
    {
        if ($email) {
            $new_email = preg_replace('/[^a-zA-Z0-9_.@]/s', '', $email);

            if (filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
                return $new_email;
            }
        }

        return null;
    }

    static public function validatePhoto($string)
    {
        $string = trim($string);
        if ($string) {

            $string = str_replace('//', '/', $string);
            $string = str_replace('_assets/', 'assets/', $string);
            $string = str_replace('Array', '', $string);
        }
        $string = trim($string);

        if (!$string || $string == "0" || $string === "") {
            return null;
        }

        //remove stuff from string

        if ($string && $string != "_assets/images/uploads/company.png") {
            $photos = explode(',', $string);
            if (count($photos) > 0) {
                return $photos[0];
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    static function validateDescription($description)
    {
        //remove anything that is not an html special character
        $new_description = preg_replace('/[^a-zA-Z0-9_ .!@#$%^&*()-+=:,<\/>]/s', '', $description);

        $badChar = array('Fuck', 'pussy', 'porn');

        $new_description = str_ireplace($badChar, '', $new_description);
        //remove bad words
        if ($new_description) {
            return $new_description;
        }
        return null;
    }

    static function validateSummary($description)
    {
        //remove anything that is not an html special character
        $new_description = preg_replace('/[^a-zA-Z0-9_ .!,:()*]/s', '', $description);

        $badChar = array('Fuck', 'pussy', 'porn');

        $new_description = str_ireplace($badChar, '', $new_description);
        //remove bad words
        if ($new_description) {
            return $new_description;
        }
        return null;
    }

    static function json_decode_with_comma($string)
    {

        if (gettype($string) == "string") {
            $string_array = json_decode($string);
            $new_string = '';
            $comma = true;
            foreach ($string_array as $value) {
                if ($comma) {
                    $new_string = $value;
                }
                $new_string = $new_string . ' ,' . $value;
                $comma = false;
            }

            return $new_string;
        }

        return null;
    }

    static function validateWebsite($string)
    {
        //remove anything that is not an html special character
        $new_description = preg_replace('/[^a-zA-Z0-9_ .!,:()*]/s', '', $string);

        $badChar = array('Fuck', 'pussy', 'porn');

        $new_description = str_ireplace($badChar, '', $new_description);
        //remove bad words
        if ($new_description) {
            return $new_description;
        }
        return null;
    }

    static public function paginator($jobs)
    {

        $new_links = [
            [
                "url" => $jobs['prev_page_url'],
                "label" => "&laquo; Previous",
                "active" => false,
            ],

            [
                "url" => $jobs['first_page_url'],
                "label" => 1,
                "active" => false,
            ],

            [
                "url" => '#',
                "label" => $jobs['current_page'],
                "active" => true,
            ],


            [
                "url" => null,
                "label" => "...",
                "active" => false,
            ],
            [
                "url" => $jobs['next_page_url'],
                "label" => "Next &raquo;",
                "active" => false,
            ]

        ];

        return $new_links;
    }



    static function cities()
    {
        return array("Chadiza", "Chama", "Chambeshi", "Chavuma", "Chembe", "Chibombo", "Chiengi", "Chilubi", "Chingola", "Chinsali", "Chinyingi", "Chipata", "Chirundu", "Chisamba", "Choma", "Gwembe", "Isoka", "Kabompo", "Kabwe", "Kafue", "Kafulwe", "Kalabo", "Kalene Hill", "Kalomo", "Kalulushi", "Kanyembo", "Kaoma", "Kapiri Mposhi", "Kasama", "Kasempa", "Kashikishi", "Kataba", "Katete", "Kawambwa", "Kazembe (Mwansabombwe)", "Kazungula", "Kibombomene", "Kitwe", "Livingstone", "Luangwa", "Luanshya", "Lufwanyama", "Lukulu", "Lundazi", "Lusaka", "Macha Mission", "Makeni", "Maliti", "Mansa", "Mazabuka", "Mbala", "Mbereshi", "Mfuwe", "Milenge", "Misisi", "Mkushi", "Mongu", "Monze", "Mpika", "Mporokoso", "Mpulungu", "Mufulira", "Mumbwa", "Muyombe", "Mwinilunga", "Nchelenge", "Ndola", "Ngoma", "Nkana", "Nseluka", "Pemba", "Petauke", "Samfya", "Senanga", "Serenje", "Sesheke", "Shiwa Ngandu", "Siavonga", "Sikalongo", "Sinazongwe", "Zambezi", "Zimba");
    }

    static public function professions()
    {
        return   array(

            "IT, Internet, Telecom" => array(
                'Data Communication and Internet Access',
                'Banking Software',
                'Networks',
                'System Integration',
                'SEO',
                'System Administrator',
                'Engineer',
                'Computer Aided Design Systems',
                'Support, Helpdesk',
                'Mobile, Wireless Technology',
                'CRM Systems',
                'Analyst',
                'Internet',
                'Startups',
                'Video Games Development',
                'Software Development',
                'Art Director',
                'Multimedia',
                'Sales',
                'Database Administrator',
                'Telecommunications',
                'E-Commerce',
                'Producer',
                'Technical Writer',
                'Entry Level, Little Experience',
                'IT Security',
                'Consulting, Outsourcing',
                'ERP',
                'Content',
                'Testing',
                'Business Development',
                'CTO, CIO, IT Director',
                'Project Management',
                'Web Engineer',
                'Marketing',
                'Web master',
            ),
            "Accounting, Management Accounting, Corporate Finance" => array(
                'CIPA',
                'Payroll Accounting',
                'Cost Accountant',
                'Capital Assets',
                'Accounts and Payments',
                'GAAP',
                'ACCA',
                'Inventory',
                'Source Documentation',
                'Financial Analysis',
                'Financial Control',
                'Finance Management',
                'Securities',
                'Auditing',
                'Treasury',
                'Tax',
                'Cashier, Cash Collector',
                'Economist',
                'Accountant',
                'Budgeting and Planning',
                'Currency Control',
                'Financial Planning Administration',
                'Entry Level, Little Experience',
                'Cost Accounting',
                'Credit Control',
                'Accounting Management',
                'IFRS',
                'Offshore'
            ),
            "Marketing, Advertising, PR" => array(
                'Print Advertising',
                'Account Planning',
                'Client Manager',
                'Political PR',
                'Product Manager',
                'Merchandising',
                'Analyst',
                'Online Marketing',
                'Art Director',
                'Market Research',
                'Outdoor Advertising',
                'Promotion, Special Offers',
                'Television Advertising',
                'Assistant',
                'Brand Management',
                'Entry Level/Little Experience',
                'Advertising Production',
                'Layout Designer',
                'Trade Marketing',
                'Consultant',
                'Radio Advertising',
                'Copywriter',
                'Secret buyer',
                'Market Researcher, Pollster',
                'Advertising Representative',
                'Product Promoter',
                'Marketing Management',
                'Designer',
                'Below The Line (BTL)',
                'PR, Marketing Communications',
                'Project Management',
            ),
            "Administrative Personnel" => array(
                'Office Manager',
                'Consecutive Interpreting',
                'Personal Assistant',
                'Workflow Management',
                'Cleaner',
                'Data Entry',
                'Simultaneous Interpreting',
                'Translation',
                'Goods Turnover',
                'Driver',
                'Entry Level, Little Experience',
                'Call Center Representative',
                'Administration and Maintenance',
                'Courier',
                'Reception, Switchboard',
                'Archivist',
                'Secretary',
                'Secretary - Evenings'
            ),
            "Banks, Investments, Finance" => array(
                'Mortgage Services',
                'Factoring',
                'Stocks, Securities',
                'Analyst',
                'Auditing, Internal Control',
                'Tax',
                'Mutual Funds',
                'Fixed Income Securities',
                'Accountant',
                'Economist',
                'Currency Control',
                'Trade Finance',
                'Entry Level, Little Experience',
                'Back Office',
                'Private Banking',
                'Financial Monitoring',
                'Money Market',
                'Currency Exchange, ATMs',
                'Small and Medium Business Lending',
                'Operations',
                'Loan Workout',
                'Risks: Operations',
                'Risks: Finance',
                'Risks: Market',
                'Auto Loans',
                'Investment Company',
                'Credit Cards, Payment Cards',
                'Branches',
                'Portfolio Investments',
                'Customer Acquisition',
                'Financial Sales',
                'Trading, Dealing',
                'Cashier Services, Cash Collection',
                'Commercial banking',
                'Project Finance',
                'Debt Appraisal, Cost of Property',
                'Risks: Other',
                'Methodology, Banking Technology',
                'New Product Development, Marketing',
                'Direct Investment',
                'Reporting',
                'Treasury and Liquidity Management',
                'Credit: Retail',
                'Budgeting',
                'Corporate Finance',
                'Settlements',
                'Correspondent, International Relationships',
                'Risks: Credit',
                'Credit',
                'Retail Banking',
                'Forex',
                'Leasing',
                'Risks: Leasing',
                'Accounting Management',
                'Issues'
            ),
            "Human Resources, Training" => array(
                'Compensation and Benefits',
                'Recruitment',
                'Human Resources',
                'Personnel Records',
                'Training',
                'People Development',
                'Entry Level, Little Experience'
            ),
            "Automotive Business" => array(
                'Production',
                'Maintenance',
                'Auto Parts',
                'Car Hire',
                'Auto Body Worker',
                'Window Tinter',
                'Auto Detailing',
                'Auto Mechanic',
                'Auto Sales',
                'Tires, Wheel Rims',
                'Entry Level/Little Experience'
            ),
            "Security" => array(
                'Video Surveillance Systems',
                'Property Security',
                'Debt Collection',
                'Fire Safety',
                'Security Manager',
                'Economic and Information Security',
                'Personal Security',
                'Cash Collector',
                'Security Guard'
            ),
            "Management" => array(
                'Marketing, Advertising, PR',
                'Investment',
                'Medicine, Pharmaceuticals',
                'Procurement Management',
                'Administration',
                'Finance',
                'IT, Internet, Multimedia',
                'Art, Entertainment, Media',
                'Construction, Real Estate',
                'Sales',
                'Science, Education',
                'Commercial Banking',
                'Manufacturing, Technology',
                'Anti-crisis Management',
                'Consulting',
                'Transport, Logistics',
                'Tourism, Hotels, Restaurants',
                'Small Business Management',
                'Law',
                'Human Resources, Training',
                'Raw Materials',
                'Insurance',
                'Sports Clubs, Fitness Clubs, Beauty Salons'
            ),
            "Raw Materials" => array(
                'Engineer',
                'Drilling',
                'Geological Exploration',
                'Mineral Surveyor',
                'Gas',
                'Coal',
                'Oil',
                'Ore',
                'Enterprise Management',
                'Entry Level, Little Experience'
            ),
            "Art, Entertainment, Media" => array(
                'Publishing',
                'Entry Level, Little Experience',
                'Other',
                'Radio',
                'Film',
                'Press',
                'Photography',
                'Fashion',
                'Design, Graphic Design, Painting',
                'Music',
                'Casinos and Gambling',
                'Television',
                'Writing, Editing',
                'Journalism'
            ),
            "Consulting" => array(
                'Management Consulting',
                'Entry Level, Little Experience',
                'Strategy',
                'Real Estate',
                'Corporate Finance',
                'Reengineering Business Processes',
                'IT',
                'Reengineering, Financial Services Outsourcing',
                'Market Research',
                'Practice Management',
                'Internet, E-Commerce',
                'Organizational Consulting',
                'Knowledge management',
                'Project Management',
                'PR Consulting'
            ),
            "Medicine, Pharmaceuticals" => array(
                'Expert Physician',
                'Certification',
                'Clinical Research',
                'Medical Advisor',
                'Junior Medical and Nursing Personnel',
                'Pharmacist',
                'Sales',
                'Pharmaceuticals',
                'Medical Equipment',
                'Medical Representative',
                'Production',
                'Veterinary Medicine',
                'Billing Desk',
                'Optician',
                'Speech and Language Pathologist',
                'Entry Level, Little Experience',
                'Psychology',
                'Lab Technician',
                'Pharmacist',
                'Attending Physician',
                'Marketing'
            ),
            "Science, Education" => array(
                'Languages',
                'Mathematics',
                'Entry Level, Little Experience',
                'Physics',
                'Engineering',
                'Teaching',
                'Computer Science, Information Systems',
                'Humanities',
                'Chemistry',
                'Economics, Management',
                'Biotechnology',
                'Earth Sciences'
            ),
            "Government, NGOs" => array(
                'Librarian',
                'Civic Organizations',
                'Local Government',
                'Research Institute',
                'Charity',
                'Government',
                'Registrar',
                'Attaché'
            ),
            "Sales" => array(
                'Telecommunications, Network Solutions',
                'Electrical Equipment/Lighting',
                'Cars, Parts',
                'Medicine, Pharmaceuticals',
                'Chemical Products',
                'Client Manager',
                'Certification',
                'Machine Tools, Heavy Equipment',
                'Retail Chains',
                'Alcohol',
                'Rolled Metal',
                'Telesales, Telemarketing',
                'Multi-level Marketing',
                'Construction Materials',
                'Agriculture',
                'Business Products',
                'FMCG',
                'Commodities Trading',
                'Sales Representative',
                'Entry Level, Little Experience',
                'Textiles, Clothing, Footwear',
                'Franchising',
                'Furniture',
                'Fuels, Lubricants, Oil, Petroleum',
                'Tenders',
                'Security Systems',
                'Dealer Networks',
                'Distribution',
                'Wholesale Trade',
                'Sales Management',
                'Cleaning Services',
                'Business Services',
                'Public Services',
                'Non-ferrous Metals',
                'Electronics, Photo, Video',
                'Plumbing',
                'Food Products',
                'Household Appliances',
                'IT Equipment',
                'Financial Services',
                'Software',
                'Process Automation Solutions',
                'Direct Sales',
                'Retail trade',
                'Sales Assistant'
            ),
            "Manufacturing" => array(
                'Aviation Industry',
                'Mechanical Engineering',
                'Furniture Manufacturing',
                'Automotive Industry',
                'Certification',
                'Metallurgy',
                'Construction Materials',
                'Tobacco Manufacturing',
                'Technician',
                'Technician, Meat and Poultry Processing',
                'Technician, Grain Production and Processing',
                'Technician, Sugar Production',
                'Entry Level, Little Experience',
                'Chief Mechanic',
                'Drafter',
                'Chief Agronomist',
                'Chief Engineer',
                'Oil Refining',
                'Project Management',
                'Jewelry Industry',
                'Occupational Safety',
                'Purchasing and Supply',
                'Nuclear Power',
                'Shop Floor Management',
                'Livestock Specialist',
                'Food Industry',
                'Engineer',
                'Pharmaceutical Industry',
                'Engineer, Meat and Poultry Processing',
                'Printing',
                'Engineer, Grain Production and Processing',
                'Engineer, Sugar Production',
                'Chemical Industry',
                'Ecologist',
                'Electric Power',
                'Metrologist',
                'Power Engineer',
                'Radio and Electronics',
                'Shipbuilding',
                'Quality Control',
                'Consumer Goods Manufacturing',
                'Timber Industry',
                'Plant Management',
                'Agricultural Production'
            ),
            "Insurance" => array(
                'Insurance - Individuals',
                'Insurance - Businesses, Organizations',
                'Loss Adjustment',
                'Auto Insurance',
                'Agent',
                'Health Insurance',
                'Business Insurance',
                'Life Insurance',
                'Underwriter',
                'Property Insurance'
            ),
            "Construction, Real Estate" => array(
                'Operation',
                'Land Management',
                'Valuation',
                'Heating, Ventilation and Air Conditioning',
                'Water and Wastewater Systems',
                'Engineer',
                'Agent',
                'Construction',
                'Surveying and Cartography',
                'Design, Architecture',
                'Construction Workers',
                'Developer',
                'Hotels, Stores',
                'Entry Level/Little Experience',
                'Non-residential Buildings',
                'Utilities',
                'Tenders',
                'Design',
                'Foreman',
                'Project Management',
                'Housing'
            ),
            "Transport, Logistics" => array(
                'Air Cargo',
                'Freight Transport',
                'Civil Aviation',
                'Warehousing',
                'Business Aviation',
                'Sea/River Transportation',
                'Container Shipping',
                'Dispatcher',
                'Driver',
                'Customs Clearance',
                'Entry Level, Little Experience',
                'Warehouse Manager',
                'Warehouse Assistant',
                'International Logistics',
                'Pipelines',
                'Expediter',
                'Rail Transportation',
                'Logistics',
                'Purchasing, Supply'
            ),
            "Tourism, Hotels, Restaurants" => array(
                'Flight Reservations',
                'Kitchen Staff',
                'Entertainment',
                'Visa Processing',
                'Tourism Business Management',
                'Travel Agent, Tour Operator',
                'Head Chef',
                'Banquets',
                'Reservations',
                'Catering',
                'Chef',
                'Entry Level, Little Experience',
                'Tour Guide',
                'Hostess',
                'Accommodation, Hospitality',
                'Doorman',
                'Hotel Management',
                'Waiter, Barman',
                'Meetings, Conferences Organization',
                'Sommelier',
                'Tourism Products',
                'Restaurant and Bar Management'
            ),
            "Lawyers" => array(
                'Mergers and Acquisitions',
                'Lawyer',
                'Paralegal',
                'Intellectual Property',
                'Antitrust Law',
                'Land Law',
                'Arbitration',
                'Contract Law',
                'Insurance Law',
                'Legal Entities Registration',
                'Maritime Law',
                'Debt Collection',
                'Securities, Capital Markets',
                'Banking Law',
                'Tax Law',
                'Copyright Law',
                'In-house Counsel',
                'Entry Level, Little Experience',
                'Labor Law',
                'Corporate Law',
                'Criminal Law',
                'International Law',
                'Real Estate',
                'Subsoil Management',
                'Compliance',
                'Legislative',
                'Family Law'
            ),
            "Sports Clubs, Fitness Clubs, Beauty Salons" => array(
                'Administration',
                'Massage Therapist',
                'Sales',
                'Hair Stylist',
                'Nail Technician',
                'Beauty',
                'Training Personnel'
            ),
            "Installation and Service" => array(
                'Service Engineer',
                'Service Center Manager',
                'Service Manager - Network and Telecommunications Technology',
                'Service Manager - Industrial Equipment',
                'Service Manager - Transportation',
                'Equipment Installation and Setup'
            ),
            "Procurement" => array(
                'Rolled Metal',
                'Procurement Management',
                'Certification',
                'Cars, Parts',
                'Construction Materials',
                'Alcohol',
                'Fuels, Lubricants, Oil, Petroleum',
                'Machine Tools, Heavy Equipment',
                'IT Equipment',
                'Pharmaceutics',
                'Food Products',
                'Business Products',
                'Electronics, Photo, Video',
                'Tenders',
                'Chemical Products',
                'FMCG',
                'Electrical Equipment/Lighting',
                'Entry Level, Little Experience'
            ),
            "Domestic Staff" => array(
                'Nanny, Au Pair, Tutor',
                'household worker, housemaid',
                'Personal Driver',
                'Gardener',
                'Chef',
                'Care Assistant',
                'Domestic Worker',
                'Private Tutor'
            ),
            "Maintenance and Operations Personnel" => array(
                'Other',
                'Coatroom Attendant',
                'Caretaker, Janitor',
                'Road Workers',
                'Metal Worker',
                'Printing Technician',
                'Picker, Packer',
                'Cabinet Maker',
                'Service Engineer',
                'Blacksmith',
                'Elevator Operator',
                'Machinist',
                'Stage Hand',
                'Excavator Operator',
                'Technician',
                'Machine Operator',
                'Rancher, Herder',
                'Rewinder Operator',
                'Laborer',
                'Plumber',
                'Grinder',
                'Loader',
                'Plasterer',
                'Electrician, Cable Technician',
                'Jeweler',
                'Fitter',
                'Turner, Miller',
                'Packer',
                'Carpenter',
                'Welder',
                'Electrician',
                'Seamstress',
                'Assembly Worker',
                'Conductor',
                'Painter'
            ),
            "Career Starters, Students" => array(
                'Marketing, Advertising, PR',
                'Medicine, Pharmaceuticals',
                'Insurance',
                'Finance, Banking, Investment',
                'Accounting',
                'Procurement',
                'IT, Internet, Multimedia',
                'Construction, Architecture',
                'Art, Entertainment, Media',
                'Science, Education',
                'Lawyers',
                'Manufacturing, Technology',
                'Transport, Logistics',
                'Tourism, Hotels, Restaurants',
                'Human Resources',
                'Raw Materials',
                'Administrative Personnel',
                'Sales',
                'Automotive Business',
                'Consulting'
            )

        );
    }

    static function currencies()
    {

        return [
            "ZMK" => "K"
        ];
    }

    static public function sectors()
    {
        $industriesMain = array('Journalists / Media', 'Web developers  /Designers', 'Marketing and Interprenuership', 'Language experts', 'SEO Online Marketing', 'Accountanting Services', 'Service Agents Services', 'Mobile developers', 'Writers', 'Photographers', 'Lawyers', 'Data Entry', 'Graphics/Visual Engineers');

        $industriesMainIcons = array('fa fa-microphone', 'fa fa-laptop', 'fa fa-laptop', 'fa fa-language', 'fa fa-laptop', 'fa fa-bank', 'fa fa-phone', 'fa fa-laptop', 'fa fa-mobile-phone', 'fa fa-pencil', 'fa fa-camera', 'fa fa-legal', 'fa fa-laptop', 'fa fa-glass',);

        $results = [];
        foreach ($industriesMain as $index => $industry) {
            $icon = '';
            if (!empty($industriesMainIcons[$index])) {
                $icon = $industriesMainIcons[$index];
            }

            $results[] = [
                "name" => $industry,
                "icon" => $industriesMainIcons[$index],
            ];
        }
        return $results;
    }

    static public function industries()
    {
        return array('Accounting and Auditing Services', 'Aerospace', 'Agriculture', 'Forestry', 'Fishing', 'Timber', 'Tobacco', 'Chemical', 'Pharmaceutical', 'Computer', 'Software', 'Construction', 'Defense', 'Arms', 'Education', 'Energy', 'Engineering', 'Electrical', 'Petroleum', 'Entertainment', 'Financial', 'Insurance', 'Food', 'Fruit', 'Health', 'Hospitality', 'Information', 'Manufacturing', 'Automotive', 'Electronics', 'Ecology', 'Pulp and paper', 'Steel', 'Shipbuilding', 'Mass media', 'Broadcasting', 'Film', 'Music', 'News media', 'Publishing', 'World Wide Web', 'Mining', 'Telecommunications', 'Internet', 'Transport', 'Water', 'Direct Selling', 'Retail', 'Retail Trade', 'Transport', 'Warehousing', 'Rental', 'Hiring', 'Real Estate Services', 'Professional Services', 'Scientific Services', 'Technical', 'Administrative', 'Support', 'Public Administration', 'Arts and Recreation', 'Religion', 'Other', 'Accountancy', 'Aviation', 'Banking', 'Consulting', 'Cosmetics', 'Engineering', 'Finance', 'General Trading', 'Government', 'Healthcare', 'Hospitality', 'Human Resource', 'Law', 'Logistics', 'Marketing', 'Media', 'Non-Profit', 'Oil and Gas', 'Retail Banking', 'Security', 'Shipping', 'Utilities');
    }

    static public function jobtypes()
    {
        return array(
            "FULL_TIME" => 'Full Time',
            "PART_TIME" => 'Part Time',
            "CONTRACTOR" => 'Contractor',
            "TEMPORARY" => 'Temporary',
            "INTERN" => 'Intern',
            "VOLUNTEER" => 'Volunteer',
            "PER_DIEM" => 'Per Diem',
            "OTHER" => 'Other',
            "FLEXIBLE" => 'Flexible',
            'FREELANCE' => 'Freelance',
            'REMOTE' => 'remote'
        );
    }
}
