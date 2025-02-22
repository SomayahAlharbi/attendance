@extends('layouts.app')
@section('title', 'Blocks')
@section('content')

<div class = 'container'>
  <div class="row">
<div class="col">
<a class="btn btn-primary" href="{{ route('blocks.create')}}" role="button">New +</a>
</div>
</div>
<button type="button" class="btn btn-link"></button>
<div class="card uper">
  <div class="card-header">
   Blocks
  </div>
  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}
    </div><br />
  @endif
  <table class="table table-striped">
    <thead>
        <tr>
          <td>ID</td>
          <td>Block Title</td>
          <td>Action</td>
        </tr>
    </thead>
    <tbody>
        @foreach($blocks as $block)
        <tr>
            <td>{{$block->id}}</td>
            <td>{{$block->block_title}}</td>
            <td><a href="{{ route('blocks.edit',$block->id)}}" class="btn btn-primary">Edit</a></td>
            <td>

            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
</div></div>
@endsection
