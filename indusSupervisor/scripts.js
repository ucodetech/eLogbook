


  $('#hideBtn').click(function(e){
    e.preventDefault();
    $('#approveForm').toggle();
  })

fetch_totUsers();

setInterval(function(){
   fetch_totUsers();
},1000);

function fetch_totUsers()
{
    var action = 'totUser';
    $.ajax({
       url:"scripts/initate.php",
       method:"POST",
       data:{action:action},
       success:function(data)
       {
         $('#totUsers').html(data);

       }
    });
}




//Fetch total feed back

fetch_totFeed();

setInterval(function(){
   fetch_totFeed();
}, 1000);

function fetch_totFeed()
{
    var action = 'totfeed';
    $.ajax({
       url:"scripts/initate.php",
       method:"POST",
       data:{action:action},
       success:function(data)
       {
         $('#totFeedback').html(data);

       }

    });
}
//Fetch total notifactions

fetch_totSuperv();

setInterval(function(){
   fetch_totSuperv();
}, 5000);

function fetch_totSuperv()
{
    var action = 'totSuperv';
    $.ajax({
       url:"scripts/initate.php",
       method:"POST",
       data:{action:action},
       success:function(data)
       {
         $('#totSupers').html(data);

       }

    });
}
// notification

fetch_totNotification();

setInterval(function(){
   fetch_totNotification();
}, 5000);

function fetch_totNotification()
{
    var action = 'totNotification';
    $.ajax({
       url:"scripts/initate.php",
       method:"POST",
       data:{action:action},
       success:function(data)
       {
         $('#totNoti').html(data);

       }

    });
}
