<?php
namespace App\Http\Model\Exports;

use App\Http\Model\AccSuppliesGoodsGroup;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AccSuppliesGoodsGroupExport implements FromCollection, ShouldAutoSize, WithEvents
{
    public function __construct($select)
   {
       $this->select = $select;
   }

    public function collection()
    {
        $a = AccSuppliesGoodsGroup::get_raw_export($this->select);
        $b = collect($a);
        if($b->count()>0){
        $key = collect($a[0])->keys();
        $key_trans = $key->map(function ($item, $key) {
          if($item == 'active'){
            return trans('action.'.$item);
          }else if($item == 'row_number'){
            return trans('global.'.$item);
          }else{
            return trans('acc_supplies_goods_group.'.$item);
          }
        });
        $b->prepend($key_trans);
      }
        return $b;
    }

    public function registerEvents(): array
  {
      return [
          AfterSheet::class    => function(AfterSheet $event) {
              $cellRange = 'A1:W1'; // All headers
              $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
              $event->sheet->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
              $event->sheet->styleCells($cellRange, [
                    //Set border Style
                    //'borders' => [
                    //    'outline' => [
                    //        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    //        'color' => ['argb' => 'EB2B02'],
                    //    ],

                    //  ],

                    //Set font style
                    'font' => [
                        'name'      =>  'OpenSans-Light',
                        'size'      =>  14,
                        'bold'      =>  true,
                        'color' => ['argb' => 'FFFFFF'],
                    ],

                    //Set background style
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => '3f51b5',
                         ]
                    ],

                ]);
          },
      ];
  }

    //public function headings(): array
    //{
    //    return [
    //        'STT',
    //        trans('menu.type'),
    //        trans('menu.parent'),
    //        trans('menu.code'),
    //        trans('menu.name'),
    //        trans('menu.name_en'),
    //        trans('menu.icon'),
    //        trans('menu.link'),
    //        trans('menu.position'),
    //        trans('action.active'),
    //    ];
    //}
}
