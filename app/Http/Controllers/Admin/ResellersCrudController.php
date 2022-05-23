<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ResellersRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use DB;
/**
 * Class ResellersCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ResellersCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Resellers::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/resellers');
        CRUD::setEntityNameStrings('resellers', 'resellers');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
      //  CRUD::column('id');
      $this->crud->enableBulkActions();
      CRUD::addColumn(
        ['name' => 'id_dist',
        'label' => 'Distributor',
        'type' => 'select',
        'entity' => 'distributor',
        'model' => "App\Models\Distributors",
        'attribute' => 'nama' ])
     ;
        CRUD::column('id_reseller')->label('ID Reseller');
        CRUD::column('nama')->label('Nama');
        CRUD::column('alamat')->label('Alamat');
        CRUD::column('notelp')->label('No Telpon/WA');
        CRUD::column('ig')->label('Instagram');
        CRUD::column('fb')->label('Facebook');

      //  CRUD::column('created_at');
      //  CRUD::column('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ResellersRequest::class);

       // CRUD::field('id');
       $data = DB::table('resellers')
       ->max('id_reseller');
       $today = date('ymd');
       $date = substr($data, 0, 6);
       $str = substr($data, 6, 10);
       if ($data == $today.$str){
           $value = $date.str_pad($str, 4, 0, STR_PAD_LEFT) +1;
       } else{
           $value = $today.str_pad(0, 4, 0, STR_PAD_LEFT) +1;
       }
      // var_dump($value);
       CRUD::addfield(
           ['name' => 'id_dist',
           'label' => 'Distributor',
           'type' => 'select',
           'entity' => 'distributor',
           'model' => "App\Models\Distributors",
           'attribute' => 'nama' ])
       ;

       CRUD::field('id_reseller')->label('ID Reseller')->value($value);
       CRUD::field('nama')->label('Nama');
       CRUD::field('alamat')->label('Alamat');
       CRUD::field('notelp')->label('No Telpon/WA');
       CRUD::field('ig')->label('Instagram');
       CRUD::field('fb')->label('Facebook');
        //CRUD::field('created_at');
       // CRUD::field('updated_at');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
    protected function setupShowOperation()
    {
        // MAYBE: do stuff before the autosetup

        // automatically add the columns
      //  $this->autoSetupShowOperation();

        // MAYBE: do stuff after the autosetup
        CRUD::addColumn(
            ['name' => 'id_dist',
            'label' => 'Distributor',
            'type' => 'select',
            'entity' => 'distributor',
            'model' => "App\Models\Distributor",
            'attribute' => 'nama' ])
         ;
            CRUD::column('id_reseller')->label('ID Reseller');
            CRUD::column('nama')->label('Nama');
            CRUD::column('alamat')->label('Alamat');
            CRUD::column('notelp')->label('No Telpon/WA');
            CRUD::column('ig')->label('Instagram');
            CRUD::column('fb')->label('Facebook');
            CRUD::addColumn([
                'name'     => 'custom_html',
                'label'    => 'Kartu Member',
                'type'     => 'closure',
               // 'value'    => '<img src="https://play-lh.googleusercontent.com/8QnH9AhsRfhPott7REiFUXXJLRIxi8KMAP0mFAZpYgd44OTOCtScwXeb5oPe1E4eP4oF">',
                'function'     => function($entry) {
                    return '
                <!DOCTYPE html>
                <html>
                  <head>
                    <script src="https://unpkg.com/konva@8.3.5/konva.min.js"></script>
                  </head>
                  <body>
                    <div id="container" type="hidden"></div>
                    <div id="buttons">
                      <button id="save">
                        Save as image
                      </button>
                    </div>
                    <script>
                      var width = window.innerWidth;
                      var height = window.innerHeight;

                      var stage = new Konva.Stage({
                        container: "container",
                        width: 500,
                        height: 250,
                      });

                      var layer = new Konva.Layer();

                      var complexText = new Konva.Text({
                        text:
                          "Nama : '.$entry->nama.'",
                        fontSize: 18,
                        fontFamily: "Calibri",
                        fill: "#555",
                        width: 500,
                        padding: 20,
                        align: "left",
                      });

                      var box = new Konva.Rect({
                        width: 500,
                        height: 250,
                        fill: "#FFFFFF",
                        cornerRadius: 30,
                      });


                      layer.add(box);
                      layer.add(complexText);
                      stage.add(layer);

                      // function from https://stackoverflow.com/a/15832662/512042
                      function downloadURI(uri, name) {
                        var link = document.createElement("a");
                        link.download = name;
                        link.href = uri;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        delete link;
                      }

                      document.getElementById("save").addEventListener(
                        "click",
                        function () {
                          var dataURL = stage.toDataURL();
                          downloadURI(dataURL, "stage.png");
                        },
                        false
                      );
                    </script>
                  </body>
                </html>';}
                // OPTIONALS
                // 'escaped' => true // echo using {{ }} instead of {!! !!}
            ]);
        // for example, let's add some new columns
    }
}
//public function membercard(){
 //       $pdf = PDF::loadView('card.member');
   //    return $pdf->download('card.pdf');
