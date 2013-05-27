//paquete lostium
var lostium = {};
//paquete jsMVC del miniframework
lostium.jsMVC = {};

/**
 * Controlador
 */
lostium.jsMVC.Controller = function() {
    //modelo
    this.model = {};
    //vista
    this.view = null;
    this.siteUrl = null;
};
/**
 * Inicialización del controlador
 *
 * @param lostium.jsMVC.View view
 */
lostium.jsMVC.Controller.prototype.init = function(controller, view) {
    this.view = view;
    this.view.controller = controller;
    this.view.init();
}

/**
 * Clase que representa la Vista
 *
 * @param lostium.jsMVC.Controller controller
 */
lostium.jsMVC.View = function(controller) {
    //modelo
    this.controller = controller;
};

/**
 * Inicialización
 */
lostium.jsMVC.View.prototype.init = function() {

}

/**
 * función que invoca a las funciones que se encargan de modificar la
 * presentación. En caso de no existir ignora la llamada.
 *
 * Ejemplo método de para una vista:
 * this.seleccionarRespuesta = function(view, action, data, htmlobject){}
 *
 */
lostium.jsMVC.View.prototype.render = function(action, data, htmlObject) {
    var actionMethod = eval('this.' + action + '');
    if (typeof actionMethod != 'undefined') {
        if (typeof htmlObject != 'undefined') {
            actionMethod(this, htmlObject);
        } else if (typeof data != 'undefined') {
            actionMethod(this, data, htmlObject);
        } else {
            actionMethod(this);
        }
    }
}