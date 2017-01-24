app.controller("CommentController",["$scope","$http","$filter", "ArticleFactory", "$routeParams",function($scope,$http,$ilter, ArticleFactory, $routeParams){

	$scope.AddCommentAction = function(){
		$scope.article.comments.push($scope.newComment.message);
		$scope.newComment.message = "";

	};

	$scope.DeleteCommentAction = function(){

	};

	$scope.ListAction = function(){
		var ai = $routeParams.id;

		if ( ai ){
			ArticleFactory.getArticle(ai).then(function(datas){
				
				$scope.article = datas;
			},function(reason){
				alert(reason);
			});
		}
		
	};

	(function(){
		$scope.ListAction();
	})();

}]);