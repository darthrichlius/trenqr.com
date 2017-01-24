/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function Cropper () {
    this.Image;
    
    //Permet de changer la taille d'une image en fonction des paramètres données
    this.Cropper_ResizeTo = function (Image,h,w) {
        if ( KgbLib_CheckNullity([Image,h,w]) )
            return;
        
        this.Image = Image;
        this.Image.height = h;
        this.Image.width = w;
        
        return this.Image;
    };
    
    //Permet de resize une image en se basant sur la modification de sa largeur.
    //Le redimensionnement se fera au pro rata. C'est a dire la forme originelle sera conservée.
    //NOTE : Si l'image est plus petite que la dimension voulue, on interprète cela comme un désir ...
    // ... de grossissement
    this.Cropper_resizeWidthKeepHeightProrata = function (Image, w) {
        if ( KgbLib_CheckNullity([Image,w]) )
            return;
        
        var _bef_w = Image.width;
        var _bef_h = Image.height;
        var square = function(i,w) {
            i.height = w;
            i.width = w;
            
            return i;
        };
        var rect = function (i,befH,befW,w) {
            var ratio = parseFloat(w)/parseFloat(befW);
            
            i.height = (parseFloat(befH)*ratio).toString();
            i.width = (parseFloat(befW)*ratio).toString();
            
            return i;
        };
        //Pourquoi envoyé si c'est pour ne rien faire
        if ( _bef_w === w ) 
            return Image;
        
        if ( _bef_w === _bef_h ) 
            return square(Image,w);
        else 
            return rect(Image,_bef_h ,_bef_w, w);
    };
    
    //Permet de cropper l'image pour lui donner la taille indiqueé en paramètre.
    //La technique consiste en prendre la forme, lui donner les caractéristiques en taille ...
    //... puis de center la forme sur l'image
    //Dans le cas où l'image est trop grande la technique est idéale.
    //Dans le cas contraire on ne fait qu'effectuer des opérations de redimensionnement
    this.Cropper_CropMiddle = function(Image,h,w) {
        
    };
    
    this.Cropper_CropMiddleVerti = function(Image,h,w) {
        
    };
    
    this.Cropper_CropMiddleHori = function(Image,h,w) {
        
    };
}
