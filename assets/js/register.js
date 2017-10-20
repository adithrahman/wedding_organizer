var app = angular.module('angjsRegis', []);

app.controller('regisCtrl', function ($scope, $http, $window) {

    $scope.back = function () {
      $window.location.href = './';
    };

    $scope.submitRegis = function() {

      if ($scope.regis.password == $scope.regis.passwordre){
        $http({
          method: "POST",
          url: "apis/web/wregister.php",
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          data: {
            email: $scope.regis.email,
            name: $scope.regis.name,
            phone: $scope.regis.phone,
            password: $scope.regis.password
          }//,
                  //
        }).then(function successCallback(response) {
          // this callback will be called asynchronously
          // when the response is available
          if (response.data == "-1") $scope.responseMessage = "Register failed";
          else if (response.data == "0"){
            $scope.responseMessage = "Email already registered";
            $scope.regisForm.$valid;
          } else if (response.data == "1"){
            $scope.responseMessage = "Register Successfull, please check your email to activate";
            $scope.regisForm.$invalid;
          }
          //else $scope.responseMessage = "Username or Password is incorrect";
        }, function errorCallback(response) {
          // called asynchronously if an error occurs
          // or server returns response with an error status.
        });
      } else {
        $scope.responseMessage = "Password not match";
      }


    };

});
