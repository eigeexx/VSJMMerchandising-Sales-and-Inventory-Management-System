function notif(){
    alert("HI");
    $('#notif').modal('show');
}


function fill(Value) {
    $('#search').val(Value);
    $('#display').hide();
 }

function add(){
    alert("found i");
    $.ajax({
        type: "POST",
        url: "addpending.php",
        data: {
            pending_ItemID: '<?php echo $_SESSION["pending_ItemID"]?>'
        },
        success: function(data) {  
            $("#dummy").html(data);
        }
    });
}

function edit(){
    $('#staticBackdrop').modal('show');
    alert("hi");
}

function search(){
    var input = $('#search').val();
    $.ajax({
        type: "POST",
        url: "search_sort.php",
        data: {
            search: input
        },
        success: function(data) {
            $("#display").html(data);
        }
    });
}

function sort(){
    var option = $('#sort').find(":selected").val();
    sessionStorage.setItem("selectedOption", option);
    var optionValue = $(this).selectedIndex;

    $.ajax({
        type: "POST",
        url: "search_sort.php",
        data: {
            selected: option
        },
        success: function(data) { 
            $("#display").html(data);
        }
    });
}

function categ(){
    var categOption = $('#categ').find(":selected").val();
    $.ajax({
        type: "POST",
        url: "search_sort.php",
        data: {
            category: categOption
        },
        success: function(data) { 
            $("#display").html(data);
        }
    });
}

    //categ on salability
    //$("#categ1").change(function(){
        function categ1(){
        var categOption = $("#categ1").find(":selected").val();
        $.ajax({
            type: "POST",
            url: "search_sort.php",
            data: {
                category1: categOption
            },
            success: function(data) { 
                $("#display").html(data);
            }
        });
        
    //});
    }
    //sort on salability
    
    
    //$("#sort1").change(function(){
    function sort1(){
        var option = $("#sort1").find(":selected").val();
        sessionStorage.setItem("selectedOption", option);
        var optionValue = $("#sort1").selectedIndex;
        $.ajax({
            type: "POST",
            url: "search_sort.php",
            data: {
                selected1: option
            },
            success: function(data) { 
                $("#display").html(data);
            }
        });
        
    //});
    }
    //search on salability
    //$("#search1").keyup(function() {
    function search1(){
        var input = $("#search1").val();
            $.ajax({
                type: "POST",
                url: "search_sort.php",
                data: {
                    search1: input
                },
                success: function(data) {
                    $("#display").html(data);
                }
            });
    //});
    }
