<?php
namespace App\Http\Model\Exports;

use App\Http\Model\Error;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ErrorExport implements FromCollection, ShouldAutoSize, WithEvents
{
  protected $select;
  protected $page;
    public function __construct($select,$page)
   {
    $this->select = $select;
    $this->page = $page;
   }

    public function collection()
    {
      $skip = ($this->page - 1) * env("EXPORT_LIMIT");
      $limit = $this->page * env("EXPORT_LIMIT");
        $a = Error::get_raw_export($this->select,$skip,$limit);
        $b = collect($a);
        if($b->count()>0){
        $key = collect($a[0])->keys();
        $key_trans = $key->map(function ($item, $key) {
          if($item == 'active'){
            return trans('action.'.$item);
          }else if($item == 'row_number'){
            return trans('global.'.$item);
          }else{
            return trans('error.'.$item);
          }
        });
        //dump(collect($a));
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
    //        trans('history_action.menu'),
    //        trans('history_action.data'),
    //        trans('history_action.user'),
    //    ];
    //}
}
