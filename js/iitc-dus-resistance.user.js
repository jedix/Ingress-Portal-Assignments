// ==UserScript==
// @id             iitc-plugin-dus-res@jedix
// @name           IITC plugin: DUS-Resistance
// @category       Info
// @version        0.0.5.20130627.111000
// @namespace      https://github.com/jonatkins/ingress-intel-total-conversion
// @description    [jedix-2013-06-12-162306] List responsible user per portal.
// @updateURL      http://ingress.jdsv.de/js/iitc-dus-resistance.meta.js
// @downloadURL    http://ingress.jdsv.de/js/iitc-dus-resistance.user.js
// @include        https://www.ingress.com/intel*
// @include        http://www.ingress.com/intel*
// @match          https://www.ingress.com/intel*
// @match          http://www.ingress.com/intel*
// @grant          none
// ==/UserScript==

function wrapper() {
// ensure plugin framework is there, even if iitc is not yet loaded
if(typeof window.plugin !== 'function') window.plugin = function() {};


// PLUGIN START ////////////////////////////////////////////////////////

// use own namespace for plugin
window.plugin.dusRes = function() {};

window.plugin.dusRes.setupCallback = function() {
  document.getElementsByTagName('head')[0].innerHTML += '<style>#dus-resistance { font-size:70%; -moz-box-sizing: border-box; border-collapse: collapse; padding: 0 4px; table-layout: fixed; width: 90%; margin:0 auto; } #dus-resistance-update { display:none; visibility:hidden; } #dus-resistance td { overflow: hidden; text-overflow: "~"; vertical-align: top; white-space: nowrap; width: calc(50% - 62px); }</style>';
  addHook('portalDetailsUpdated', window.plugin.dusRes.addInfo);
}

window.plugin.dusRes.addInfo = function(d) {
  $('.linkdetails').before('<table id="dus-resistance"><tr><th colspan="2">Responsible for recharging</th></tr><tr><th>Player</th><th>Last update</th></table><iframe id="dus-resistance-update" name="dus-resistance-update"></iframe>');
  window.plugin.dusRes.getPlayers(d.portalDetails);
}

window.plugin.dusRes.getPlayers = function (d) {
var url = "http://ingress.jdsv.de/plugin.php?lat=" + d.locationE6.latE6 + "&lng=" + d.locationE6.lngE6 + "&nick=" + PLAYER.nickname + "&portalname=" + encodeURIComponent(d.portalV2.descriptiveText.TITLE) + "&portalfaction=" + d.controllingTeam.team + "&portalowner=" + getPlayerName(d.captured.capturingPlayerId) + "&portalownersince=" + d.captured.capturedTime + "&v=0.0.5";
$('body').append('<script type="text/javascript" src="' + url + '"></script>');
}

var setup = function () {
    window.plugin.dusRes.setupCallback();
}


// PLUGIN END //////////////////////////////////////////////////////////

if(window.iitcLoaded && typeof setup === 'function') {
  setup();
} else {
  if(window.bootPlugins)
    window.bootPlugins.push(setup);
  else
    window.bootPlugins = [setup];
}
} // wrapper end
// inject code into site context
var script = document.createElement('script');
script.appendChild(document.createTextNode('('+ wrapper +')();'));
(document.body || document.head || document.documentElement).appendChild(script);
