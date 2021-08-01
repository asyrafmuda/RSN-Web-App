<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ConvertDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:date_article';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trim Description Campaign For Short Description';

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
        $articles = Article::all();

        foreach ($articles as $article) {
            $nmeng = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'ogos', 'september', 'october', 'november', 'december');
            $nmtur = array('Januari', 'Februari', 'Mac', 'April', 'Mei', 'Jun', 'Julai', 'Ogos', 'September', 'Oktober', 'November', 'Disember');

            $to_date = Carbon::parse($article->publish_at)->format('d F Y');
            $to = str_ireplace($nmeng, $nmtur, $to_date);


            $article->publish_date_at = $to;
            $article->save();
        }
    }
}
