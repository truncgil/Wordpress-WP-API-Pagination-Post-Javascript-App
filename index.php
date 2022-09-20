<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scroller App</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>

    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>    
    <script>
        $(function(){
            const mainUrl = "http://localhost/mydiarrheasong/";
            const perPage = 15
            const postContent = $(".post:eq(0)").html();
            let currentPage;
            let letsContinue = true;
            
            function getPostUrl(page) {
                return mainUrl + "wp-json/wp/v2/posts?orderby=date&per_page="+perPage+"&page=" + page
            }

            function getPostRender(pageNumber, beforeClear=true) {
                if(letsContinue) {
                    letsContinue = false;
                    console.log(pageNumber);
                    currentPage = pageNumber;

                    $(".paginate .btn").removeClass("btn-success");
                    $(".paginate .btn[page='"+currentPage+"']").addClass("btn-success");

                    if(beforeClear) {
                        $(".post-zone").html("Loading...");
                    }

                    $.getJSON(getPostUrl(pageNumber), function( data ) {
                        var items = [];

                    if(beforeClear) {
                        $(".post-zone").html("");
                    }
                    //console.log(data);
                    $.each( data, function( key, val ) {
                        //console.log(val.excerpt.rendered);
                        $(".post-zone").append(
                                postContent
                                .replace("{title}", val.title.rendered)
                                .replace("{content}", val.excerpt.rendered)
                                
                                );
                        });

                    })
                    .fail(function(xhr, error, status) {
                        $(".post-zone").html("");
                    })
                    .done(function(xhr, error, status) {
                        letsContinue = true;
                    });
                }
                
            }

            getPostRender(1,true);

            $(".paginate .btn").on("click", function(){
                
                $(".paginate .btn").removeClass("btn-success");
                $(this).addClass("btn-success");
                let page = eval($(this).attr("page"));
                currentPage = page;
                getPostRender(page,true);
            });

            $(window).scroll(function() {
                if($(window).scrollTop() + $(window).height() > $(document).height() - 200) {
                    getPostRender(currentPage+1, false);
                }
            });
            

        });
    </script>
</head>
<body>

<div class="jumbotron text-center" style="margin-bottom:0">
  <h1>Scroller App UWPD MULTI</h1>
</div>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <a class="navbar-brand" href="#">ScrollerApp</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="#">Link</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Link</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Link</a>
      </li>    
    </ul>
  </div>  
</nav>

<div class="container" style="margin-top:30px;margin-bottom:30px;">
  <div class="row ">
        <div class="col-12 post-zone">
            <div class="post">
                <div class="card">
                    <div class="card-body">
                    <h4 class="card-title">{title}</h4>
                    <p class="card-text">{content}</p>
                    <a href="#" class="card-link">Card link</a>
                    <a href="#" class="card-link">Another link</a>
                    </div>
                </div>
            </div>
        </div>
  </div>
</div>

<div class="bg-dark text-light text-center" style="margin-bottom:0;position:fixed;bottom:0px;left:0px;width:100%;">
    <div class="container">
        <div class="btn-group paginate">
                <?php for($k=1;$k<=20;$k++) {
                    ?>
                    <div page="<?php echo $k ?>" class="btn btn-link text-white"><?php echo($k); ?></div>
                    <?php 
                } ?>
        </div>
    </div>
</div>

<style>
    .card {
        margin:20px 0;
    }
</style>

</body>
</html>