@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Проекты</div>

                <div class="card-body">
                    <a href="/projects/add" class="btn btn-success" style="margin-bottom:10px;">
                        + Добавить новый проект
                    </a>

                    <table class="table">
                        <tr>
                            <td>#</td>
                            <td>Название</td>
                            <td>Редактировать</td>
                            <td>Удалить</td>
                        </tr>
                        @foreach($projects as $project)
                        <tr>
                            <td>
                                {{$project->id}}
                            </td>
                            <td>
                                <a target="_blank" href="/projects/{{$project->id}}">{{$project->name}}</a>
                            </td>
                            <td>
                                <a class="btn btn-primary" href="/projects/edit/{{$project->id}}">Настройки</a>
                            </td>
                            <td>
                                <a class="btn btn-danger" href="/projects/delete/{{$project->id}}">Удалить</a>
                            </td>
                        </tr>
                        @endforeach
                    </table>



                </div>
            </div>
        </div>
    </div>
</div>
@endsection
