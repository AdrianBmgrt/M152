<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>Effacer un post</h1>
                    </div>
                    <form action="#" method="post">
                        <div class="alert alert-danger fade in">
                            <p>Etes-vous sûr de vouloir effacer ça ?</p><br>
                            <p>
                                <input type="submit" name="btnSubmit" value="Yes" class="btn btn-danger">
                                <a href="?action=home" class="btn btn-default">No</a>
                            </p>
                        </div>
                        <?php echo $errorMsg; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>