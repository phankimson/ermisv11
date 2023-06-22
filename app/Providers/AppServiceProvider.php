<?php

namespace App\Providers;

use App\Http\Model\Macros\CollectionMacro;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use \Maatwebsite\Excel\Writer;
use \Maatwebsite\Excel\Sheet;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      Writer::macro('setCreator', function (Writer $writer, string $creator) {
          $writer->getDelegate()->getProperties()->setCreator($creator);
      });
      Sheet::macro('setOrientation', function (Sheet $sheet, $orientation) {
          $sheet->getDelegate()->getPageSetup()->setOrientation($orientation);
      });

      Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
          $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
      });
      Collection::mixin(new CollectionMacro);

      Relation::enforceMorphMap([
        'AccSettingAccountGroup' => 'App\Http\Model\AccSettingAccountGroup'
     ]);
    }
}
