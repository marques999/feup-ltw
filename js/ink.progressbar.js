Ink.createModule('Ink.UI.ProgressBar', '1', ['Ink.UI.Common_1', 'Ink.Dom.Selector_1'], function( Common, Selector ) {
	'use strict';

	function ProgressBar(){
		Common.BaseUIComponent.apply(this, arguments);
	}

	ProgressBar._name = 'ProgressBar_1';
	ProgressBar._optionDefinition = {
		startValue: ['Number', 0],
		onStart: ['Function', function () {}],
		onEnd: ['Function', function () {}]
	};

	ProgressBar.prototype = {
		_init: function(){
			this._value = this._options.startValue;
			this._elementBar = Selector.select('.bar',this._element);

			if (this._elementBar.length < 1 ){
				throw new Error('[Ink.UI.ProgressBar] :: Bar element not found');
			}

			this._elementBar = this._elementBar[0];
			this.setValue( this._options.startValue );
		},

		setValue: function( newValue ){
			this._options.onStart.call(this, this._value);

			newValue = parseInt(newValue,10);
			if( isNaN(newValue) || (newValue < 0) ){
				newValue = 0;
			 else if( newValue>100 ){
				newValue = 100;
			}

			this._value = newValue;
			this._elementBar.style.width = this._value + '%';
			this._options.onEnd.call(this, this._value);
		}
	};

	Common.createUIComponent(ProgressBar);

	return ProgressBar;
});