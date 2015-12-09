<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {block name='title'}
        <title>Form testing</title>
    {/block}
    <link rel='stylesheet' type='text/css' href='/css/bootstrap.css' />
    <link rel='stylesheet' type='text/css' href='/css/bootstrap-theme.css' />

    <script type='text/javascript' src='/js/jquery-1.9.1.min.js'></script>
    <script type='text/javascript' src='/js/jquery-ui-1.10.0.custom.min.js'></script>
    <script type='text/javascript' src='/js/bootstrap.js'></script>
    <script type='text/javascript' src='/js/bootstrap.file-input.js'></script>
    
<!-- <style>
    .btn-file {
    position: relative;
    overflow: hidden;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
}
</style> -->
    
</head>

<body class="main">

<div class="container">
  {include file='component/navbar'}
  <div class="row">
    <div class="col-md-6">
      {block name='mainContent'}        
      {/block}
    </div>
    <div class="col-md-6">
    </div>
</div>

</body>

<script type="text/javascript">
    $('input[type=file]').bootstrapFileInput();
    $('.file-inputs').bootstrapFileInput();
</script>

</html>