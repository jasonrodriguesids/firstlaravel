<?php

namespace App\Console\Commands;

use App\Models\Caesar;

use Illuminate\Console\Command;

class TestCom extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:command {what} {string?} {key?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is used to test my various code fragments';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //question for
        $command = $this->argument('what');
        if($command == "findlongstr")
        {
            $this->findLongestString("The quick brown fox jumped over the lazy lagoon");
        }
        if($command == "dict")
        {
            $dict = file('resources\dict\words.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $searchstring = "test";
            if($this->argument('string')!= null)
                $searchstring = $this->argument('string');
            $search_array = array_combine(array_map('strtolower', $dict), $dict);
            try
            {
                if($search_array[strtolower($searchstring)])
                {
                    print("Found ".$searchstring);
                }
            }
            catch(\Exception $e)
            {
                print("Did not find ".$searchstring);
            }
        }
        if($command == "caesar")
        {
            if($this->argument('string') != null && $this->argument('key') == null)
            {
                dump("Testing with message and random key");
                $caesar = new Caesar($this->argument('string'));
                dump("Enciphered ".$caesar->encrypt());
                $caesar->setKey(null);
                dump("Deciphered: ". $caesar->decrypt());
            }
            else if($this->argument('key') != null)
            {
                dump("Testing with message and given key");
                $caesar = new Caesar($this->argument('string'), $this->argument('key'));
                dump("Enciphered ".$caesar->encrypt());
                $caesar->setKey(null);
                dump("Deciphered: ". $caesar->decrypt());
            }
            else
            {
                dump("Testing with test message and random key. Default: This is the default test message");
                $caesar = new Caesar("This is the default test message");
                dump("Enciphered ".$caesar->encrypt());
                $caesar->setKey(null);
                dump("Deciphered: ". $caesar->decrypt());
            }

            // dump($caesar->encipher());


        }
        return 0;
    }

    public function findLongestString($str)
    {

            $inputArr = explode(" ", $str);
            $lengths = array_map('strlen', $inputArr);
            $longestString = $inputArr[array_search(max($lengths), $lengths)];
            print($str);
            print_r($inputArr);
            print($longestString);
            return $longestString;
    }

    function cipher($ch, $key)
    {
        if (!ctype_alpha($ch))
            return $ch;

        $offset = ord(ctype_upper($ch) ? 'A' : 'a');
        return chr(fmod(((ord($ch) + $key) - $offset), 26) + $offset);
    }

    function encipher($input, $key)
    {
        $output = "";

        $inputArr = str_split($input);
        foreach ($inputArr as $ch)
            $output .= $this->cipher($ch, $key);

        return $output;
    }

    function decipher($input, $key)
    {
        return $this->encipher($input, 26 - $key);
    }
}
