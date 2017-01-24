//Permet d'initialiser l'application
var app = angular.module("pg-angular",['ngRoute']);

app.directive("myngEnter",function(){
	return function(scope,elem,attrs) {
		console.log(elem);
		console.log(attrs.type);

		elem.bind("blur",function(e){
			console.log("Has Blur");
		});
	};
});	

//Permet d'ajouter un controller
//app.controller("main",function($scope, $http, $filter) {
//Ce formattage est plus fiable pour la mimification plutôt que celui du dessus
app.controller("main", ['$scope','$http','$filter',function($scope, $http, $filter) {
    var globalvar = "Global Var";

    $scope.myPrimitive = 50;
	$scope.myObject = {aNumber: 11};


    $scope.reactions = [
	    {
	    	id : 1,
	    	text : "Comment 1"
	    },
	    {
			id : 2,
	    	text : "Comment 2"	
	    },
    ];

    $scope.users = [
    	{
    		name	: 'marc',
    		age		: 18,
    		city	: "Paris"
    	},
    	{
    		name	: 'jules',
    		age		: 18,
    		city	: "Lyon"
    	},
    	{
    		name	: 'abdel',
    		age		: 18,
    		city	: "Marseille"
    	},
    	{
    		name	: 'joseph',
    		age		: 18,
    		city	: "Tours"
    	},
    	{
    		name	: 'dieudonné',
    		age		: 18,
    		city	: "Montreal"
    	}
    ];
    $scope.users = $filter("orderBy")($scope.users,"name");
    $scope.$watch("query",function(){
    	$scope.users = $filter("filter")($scope.users,$scope.query);
    },true);

    //ALERT
    $scope.alertThis = function(bar) {
        alert(bar);
    };

    //AJAX
    $scope.serverSayHello = function(){
        $http.get("server.php?what=sayHello").success(function(datas){
            $scope.server_hw = JSON.stringify(datas);
        });
    };


    //ADD IN lIST (JS)
    $scope.AddReaction = function(rwRctxt){
    	var id = $scope.reactions.length + 1;

    	$scope.reactions.push({
    		"id" : id,
    		"text" : rwRctxt
    	});
    	
    	$scope.newReaction = "";
    };

	//WATCH VAR
    var watchReactions = function(){
    	$scope.react_length = $scope.reactions.length;
    };
    $scope.$watch("reactions",watchReactions,true);

    //BLUR
    $scope.myBlur = function(){
    	alert("test");
    };
    
    setTimeout(function(){
        $scope.serverSayHello();
    },2000);

}]);