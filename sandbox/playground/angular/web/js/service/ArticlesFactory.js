app.factory("ArticleFactory",["$http","$q",function($http, $q){
	var factory = {
		articles: [],
		getArticles: function(){
			//On récupère les données depuis le serveur ...
			var deferred = $q.defer();

			if ( factory.articles.length ) {
				deferred.resolve(factory.articles);
			} else {
				deferred.notify("Debut du chargement du fichier des données");
				$http.get("datasrc_articles.json?23")
					.success(function(datas){
						factory.articles = datas;
						deferred.resolve(datas);
					})
					.error(function(){
						deferred.reject("ERR : Impossible de récupérer les données !");
					});
			}

			//..On renvoie les données via un promise
			return deferred.promise;
		},
		getArticle: function(ai){
			var deferred = $q.defer();

			if ( factory.articles.length ) {
				deferred.resolve(factory.articles[ai]);
			} else {
				
				$http.get("datasrc_articles.json?23").success(function(datas){
					factory.articles = datas;
					deferred.resolve(datas[ai]);
				}).error(function(){
					deferred.reject("ERROR : GetArticle()!");
				});

			}

			return deferred.promise;
		}
	};

	return factory;
}]);