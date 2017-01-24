

app.config(['$routeProvider', function($routeProvider){
	$routeProvider
		.when("/",{
			templateUrl: "partials/articles.html",
			controller : "ArticleController"
		})
		.when("/articles/comments/:id",{
			templateUrl: "partials/singleArticle.html",
			controller : "CommentController"
		})
		.otherwise({
			redirectTo: "www.google.fr"
		})
}]);