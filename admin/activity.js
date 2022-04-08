update_admin_login();

        function update_admin_login()
        {
            var action = 'update_admin';
            $.ajax({
               url:"scripts/initate.php",
               method:"POST",
               data:{action:action},
               success:function(response)
               {
                 // console.log(response);
               }


            });
        }
   setInterval(function(){
     update_admin_login();
  }, 1000);


// FEcth active users
fetch_user_login();

setInterval(function(){
    fetch_user_login();
}, 1000);

function fetch_user_login()
{
    var action = 'fetch_data';
    $.ajax({
        url:"scripts/initate.php",
        method:"POST",
        data:{action:action},
        success:function(data)
        {
            // console.log(data);
            $('#showCurrentLoggedInS').html(data);

        }

    });
}
