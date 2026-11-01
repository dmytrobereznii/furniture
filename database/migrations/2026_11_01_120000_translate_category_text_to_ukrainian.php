<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class TranslateCategoryTextToUkrainian extends Migration
{
    private const CATEGORIES = [
        'kuhni' => ['Кухні', 'Кухни', 'Мебельный мастер | Кухни'],
        'shkafy-prihozhie' => ['Шафи та передпокій', 'Шкафы и прихожие', 'Мебельный мастер | Шкафа и прихожие'],
        'ofisnaya-torgovaya-mebel' => ['Офісні та торгові меблі', 'Офисная и торговая мебель', 'Мебельный мастер | Офисная и торговая мебель'],
        'garderoby-tumby' => ['Гардероби та тумби', 'Гардеробы и тумбы', 'Мебельный мастер | Гардеробы и тумбы'],
        'detskaya-mebel' => ['Дитячі меблі', 'Детская мебель', 'Мебельный мастер | Детская мебель'],
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (self::CATEGORIES as $slug => [$name, $oldName, $oldTitle]) {
            DB::table('categories')->where('slug', $slug)->update(['name' => $name]);
            DB::table('meta_data')->where('category_slug', $slug)->update(['title' => 'Меблевий майстер | ' . $name]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (self::CATEGORIES as $slug => [$name, $oldName, $oldTitle]) {
            DB::table('categories')->where('slug', $slug)->update(['name' => $oldName]);
            DB::table('meta_data')->where('category_slug', $slug)->update(['title' => $oldTitle]);
        }
    }
}
