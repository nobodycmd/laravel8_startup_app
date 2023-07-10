@extends('layouts.layout')

@section('layout')

    <div class="container">
        <div class="panel panel-info">
            <div class="panel-heading">CRUD</div>
            <div class="panel-body">

                <div>

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#model"   role="tab" data-toggle="tab">model</a></li>
                        <li role="presentation"><a href="#controller"  role="tab" data-toggle="tab">controller</a></li>
                        <li role="presentation"><a href="#route"  role="tab" data-toggle="tab">route</a></li>
                        <li role="presentation"><a href="#index"  role="tab" data-toggle="tab">index</a></li>
                        <li role="presentation"><a href="#view" role="tab" data-toggle="tab">view</a></li>
                        <li role="presentation"><a href="#edit" role="tab" data-toggle="tab">edit</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="model">
<pre>


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class {{$className}} extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $table='{{$table}}';
}


</pre>
                        </div>
                        <div role="tabpanel" class="tab-pane " id="controller">


                            <pre>


namespace App\Http\Controllers\Admin;


use App\Models\{{$className}};



use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;


class {{$className}}Controller extends Controller
{

    public function __construct()
    {

    }

    public function index(){
        $limit = request()->input('limit',20);
        $page = request()->input('page',1);
        $query = {{$className}}::query();
<?php
                                foreach ($aryColumn as $one){
                                    echo "if(\$v = request()->input('$one->Field','')){" . PHP_EOL;
                                    echo "                \$query->where('$one->Field','=',\$v);" . PHP_EOL;
                                    echo "            }" . PHP_EOL;
                                }
                                ?>
        $paginator = $query->paginate($limit);
        $list = $query->offset(($page - 1) * $limit)->limit($limit)->orderByDesc('id')->get();
        return view("admin.{{strtolower($className)}}.index",compact('paginator','list'));
    }


    public function view(){
        $model = {{$className}}::find(request()->input('id',0));
        return view("admin.{{strtolower($className)}}.view",compact('model'));
    }

    public function edit(){
        $model = {{$className}}::query()->firstOrNew(['id'=>request()->input('id',0)]);
                                if(request()->isMethod('get'))
        return view("admin.{{strtolower($className)}}.edit",compact('model'));
<?php
                                foreach ($aryColumn as $one){
                                    echo "if(\$v = request()->input('$one->Field','')){" . PHP_EOL;
                                    echo "                \$model->$one->Field = \$v;" . PHP_EOL;
                                    echo "            }" . PHP_EOL;
                                }
                                ?>

        if($model->save())
        {
            return get_response_message(0, 'succ', []);
        }

        return get_response_message(500, 'fail', []);

    }



}

                            </pre>


                        </div>
                        <div role="tabpanel" class="tab-pane " id="route">
                            <pre>

    Route::prefix('{{strtolower($className)}}')->name('{{strtolower($className)}}.')->group(function () {
        Route::match(['get','post'],'index', [{{$className}}Controller::class, 'index'])->name('index');
        Route::match(['get','post'],'view', [{{$className}}Controller::class, 'view'])->name('view');
        Route::match(['get','post'],'edit', [{{$className}}Controller::class, 'edit'])->name('edit');
    });

                            </pre>
                        </div>
                        <div role="tabpanel" class="tab-pane " id="index">
<pre>
<?php
    echo "@extends('layouts.layout')" . PHP_EOL;;

    echo "@section('layout')" . PHP_EOL;

    $sf = '';
    foreach($aryColumn as $one){
        $fieldName = $one->Field;
        $comment = $one->Comment ? $one->Comment : $one->Field;

        $sf .= <<<EOF
<!-- <div class="form-group">
    <label>$comment</label>
    <input type="text" class="form-control" name="$fieldName" id="$fieldName"  autocomplete="false" placeholder="$comment" value="{{request()->input('$fieldName','')}}">
</div> -->
\n
EOF;

    }


    $s =<<<EOF
<div class="container-fluid" style="padding-top:15px;">
<form method="post" class="form-inline">
@csrf
$sf
            <input type="submit" class="btn btn-success"/>
        </form>

<div class="table-responsive">
        <table class="table">
            <tbody>
            <tr>
EOF;

    echo htmlentities($s) . PHP_EOL;
    foreach($aryColumn as $one){
        $s = "<th>".($one->Comment ? $one->Comment : $one->Field)."</th>" . PHP_EOL;
        echo htmlentities($s) . PHP_EOL;
    }
    echo  htmlentities("<th>操作</th>") . PHP_EOL;
    echo  htmlentities("</tr>") . PHP_EOL;

    echo htmlentities('<?php/** @var App\\Models\\'. $className .'[] ' . ' $list */ ?>') . PHP_EOL;


    echo htmlentities("<?php foreach(\$list as \$one){ ?>") . PHP_EOL;
    echo htmlentities("<tr>") . PHP_EOL;
    foreach($aryColumn as $one){
        echo htmlentities("<td>") . '{{$one->'. $one->Field.'}}' . htmlentities("</td>") . PHP_EOL;
    }
    echo htmlentities("<td>") . PHP_EOL;
    echo htmlentities("</td>") . PHP_EOL;
    echo htmlentities("</tr>") . PHP_EOL;
    echo htmlentities("<?php } ?>");

    echo htmlentities("
            </tbody>
        </table>
</div>") . PHP_EOL;
    echo '{{$paginator->links()}}' . PHP_EOL;
    echo "@endsection" . PHP_EOL. PHP_EOL. PHP_EOL;
    echo "@section('script')" . PHP_EOL;
    echo "@endsection" . PHP_EOL;
    ?>
</pre>

                        </div>
                        <div role="tabpanel" class="tab-pane " id="view">

                            <pre>
<?php
                                echo "@extends('layouts.adminpop')" . PHP_EOL;;

                                echo "@section('layout')" . PHP_EOL;


                                echo htmlentities('<?php/** @var App\\Models\\'. $className .' ' . ' $model */ ?>') . PHP_EOL;

                                $s =<<<EOF
<div class="container">
<div class="panel panel-primary">

  <div class="panel-heading">查看</div>
  <div class="panel-body">

     <table class="table">
            <colgroup>
                <col width="180">
                <col>
            </colgroup>
            <tbody>
EOF;

                                echo htmlentities($s) . PHP_EOL;
                                foreach($aryColumn as $one){
                                    echo  htmlentities("<tr>") . PHP_EOL;
                                    $s = "<td>".($one->Comment ? $one->Comment : $one->Field)."</td>";
                                    echo htmlentities($s) . PHP_EOL;
                                    $s = '<td>{{$model->'.$one->Field.'}}</td>';
                                    echo htmlentities($s) . PHP_EOL;
                                    echo  htmlentities("</tr>") . PHP_EOL;
                                }


                                echo htmlentities("
            </tbody>
        </table>") . PHP_EOL;

                                $s = <<<EOF

    </div>

</div>
EOF;
                                echo htmlentities($s) . PHP_EOL;




                                echo "@endsection" . PHP_EOL. PHP_EOL. PHP_EOL;
                                echo "@section('script')" . PHP_EOL;
                                echo "@endsection" . PHP_EOL;
                                ?>
</pre>



                        </div>
                        <div role="tabpanel" class="tab-pane " id="edit">


                            <pre>

                                <?php
                                echo "@extends('layouts.adminpop')" . PHP_EOL;;

                                echo "@section('layout')" . PHP_EOL;

                                echo htmlentities('<?php/** @var App\\Models\\'. $className .' ' . ' $model */ ?>') . PHP_EOL;



                                $s =<<<EOF
<form method="post" name="f" autocomplete="off">
@csrf
     <table class="table">
            <colgroup>
                <col width="180">
                <col>
            </colgroup>
            <tbody>
EOF;

                                echo htmlentities($s) . PHP_EOL;
                                foreach($aryColumn as $one){
                                    $fieldName = $one->Field;
                                    echo  htmlentities("<tr>") . PHP_EOL;

                                    $s = "<td>".($one->Comment ? $one->Comment : $one->Field)."</td>";
                                    echo htmlentities($s) . PHP_EOL;
                                    if($inputAll[$fieldName] == 'text'){
                                        $s = '<td><input name="'.$fieldName.'"  autocomplete="false"  value="{{$model->'.$fieldName.'}}"  class="form-control"  /></td>';
                                    }
                                    if($inputAll[$fieldName] == 'date'){
                                        $s = '<td><input name="'.$fieldName.'"  autocomplete="false"  value="{{$model->'.$fieldName.'}}"  class="form-control"  /></td>';
                                    }
                                    if($inputAll[$fieldName] == 'datetime'){
                                        $s = '<td><input name="'.$fieldName.'"  autocomplete="false"  value="{{$model->'.$fieldName.'}}"  class="form-control"  /></td>';
                                    }
                                    if($inputAll[$fieldName] == 'file'){
                                        $s = '<td><input name="'.$fieldName.'" id="'.$fieldName.'upload_url" type="hidden"   value="{{$model->'.$fieldName.'}}"   /><a type="button" class="btn btn-success" id="'.$fieldName.'upload"></td>';
                                    }
                                    if($inputAll[$fieldName] == 'dropdown'){
                                        $s = '<td><select name="'.$fieldName.'"  id="'.$fieldName.'"  autocomplete="false"  value="{{$model->'.$fieldName.'}}"  class="form-control"></select></td>';
                                    }
                                    echo htmlentities($s) . PHP_EOL;

                                    echo  htmlentities("</tr>") . PHP_EOL;
                                }
                                echo  htmlentities("<tr>") . PHP_EOL;
                                echo  htmlentities("<td colspan='2'><a class='btn btn-success' onclick=\"commonPost('f')\">保存</a></td>") . PHP_EOL;
                                echo  htmlentities("</tr>") . PHP_EOL;

                                echo htmlentities("
            </tbody>
        </table>") . PHP_EOL;

                                $s = <<<EOF
</form>
EOF;
                                echo htmlentities($s) . PHP_EOL;


                                echo "@endsection" . PHP_EOL;
                                echo "@section('script')" . PHP_EOL;
                                echo "@endsection" . PHP_EOL;
                                ?>

                            </pre>


                        </div>
                    </div>



@endsection

@section('script')

<?php
    foreach ($inputAll as $column => $value){
        if($value == 'file'){
            ?>
$("#<?= $column ?>upload").uploadFile({
    url:"{{route('admin.common.webFileUpload')}}",
    fileName:"file",
    maxFileCount:1,
    dragDrop:true,
    returnType: "json",
    statusBarWidth:600,
    dragdropWidth:600,
    maxFileSize:15000*1024,
    showPreview:true,
    previewHeight: "100px",
    previewWidth: "100px",
    onSuccess:function(files,res,xhr,pd)
    {
        console.log((res))
        $('#<?= $column ?>upload_url').val(res.data.url)
    },
})

<?php
        }

if($value == 'date'){
?>
layui.laydate.render({
    elem: '#<?= $column ?>',
    type: 'date',
    // range: '~',
    trigger: 'click',
    ready:function (){
        document.activeElement.blur();
    },
});
    <?php
    }

    if($value == 'datetime'){
    ?>
    layui.laydate.render({
        elem: '#<?= $column ?>',
        type: 'datetime',
        // range: '~',
        trigger: 'click',
        ready:function (){
            document.activeElement.blur();
        },
    });
    <?php
    }

    }
    ?>

@endsection
