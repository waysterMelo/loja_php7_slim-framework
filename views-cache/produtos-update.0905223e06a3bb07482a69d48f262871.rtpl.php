<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Editar Produto
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="/admin/produtos">Produtos</a></li>
            <li class="active"><a href="/admin/produtos/:id/editar">Editar</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Editar Produto</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" action="/admin/produtos/<?php echo htmlspecialchars( $produtos["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="descricao">Nome do Produto</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" value="<?php echo htmlspecialchars( $produtos["descricao"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                        </div>
                        <div class="form-group">
                            <label for="preco">Preço</label>
                            <input type="text" class="form-control" id="preco" name="preco" step="0.01" value="<?php echo htmlspecialchars( $produtos["preco"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                        </div>
                        <div class="form-group">
                            <label for="width">Largura</label>
                            <input type="text" class="form-control" id="width" name="tamanho" step="0.01" value="<?php echo htmlspecialchars( $produtos["tamanho"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                        </div>
                        <div class="form-group">
                            <label for="height">Altura</label>
                            <input type="text" class="form-control" id="height" name="altura" step="0.01" value="<?php echo htmlspecialchars( $produtos["altura"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                        </div>
                        <div class="form-group">
                            <label for="length">Comprimento</label>
                            <input type="text" class="form-control" id="length" name="comprimento" step="0.01" value="<?php echo htmlspecialchars( $produtos["comprimento"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                        </div>
                        <div class="form-group">
                            <label for="weight">Peso</label>
                            <input type="text" class="form-control" id="weight" name="peso" step="0.01" value="<?php echo htmlspecialchars( $produtos["peso"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                        </div>
                        <div class="form-group">
                            <label for="url">Url</label>
                            <input type="text" class="form-control" id="url" name="url" value="<?php echo htmlspecialchars( $produtos["url"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                        </div>
                        <div class="form-group">
                            <label for="image">Foto</label>
                            <input type="file" class="form-control" name="img" id="image" value="<?php echo htmlspecialchars( $produtos["image"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                            <img src="<?php echo htmlspecialchars( $produtos["image"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" alt="" style="width: 150px;">
                        </div>
                        <div class="form-group-lg">
                            <button type="submit" class="btn btn-success" style="margin-top: 5px">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>
