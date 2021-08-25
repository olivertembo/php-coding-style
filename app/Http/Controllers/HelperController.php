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



    public function stringContains($string, $array)
    {
        foreach ($array as $el) {
            if (strpos($string, $el) === 0) {
                return true;
            }
        }
        return false;
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


}
