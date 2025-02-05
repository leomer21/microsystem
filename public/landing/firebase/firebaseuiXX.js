(function() {
     /*
 
  Copyright 2015 Google Inc. All Rights Reserved.
 
  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at
 
       http://www.apache.org/licenses/LICENSE-2.0
 
  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License.
 */
     var componentHandler = {
         upgradeDom: function(optJsClass, optCssClass) {},
         upgradeElement: function(element, optJsClass) {},
         upgradeElements: function(elements) {},
         upgradeAllRegistered: function() {},
         registerUpgradedCallback: function(jsClass, callback) {},
         register: function(config) {},
         downgradeElements: function(nodes) {}
     };
     componentHandler = function() {
         var registeredComponents_ = [];
         var createdComponents_ = [];
         var componentConfigProperty_ = "mdlComponentConfigInternal_";
 
         function findRegisteredClass_(name, optReplace) {
             for (var i = 0; i < registeredComponents_.length; i++)
                 if (registeredComponents_[i].className === name) {
                     if (typeof optReplace !== "undefined") registeredComponents_[i] = optReplace;
                     return registeredComponents_[i]
                 } return false
         }
 
         function getUpgradedListOfElement_(element) {
             var dataUpgraded = element.getAttribute("data-upgraded");
             return dataUpgraded ===
                 null ? [""] : dataUpgraded.split(",")
         }
 
         function isElementUpgraded_(element, jsClass) {
             var upgradedList = getUpgradedListOfElement_(element);
             return upgradedList.indexOf(jsClass) !== -1
         }
 
         function createEvent_(eventType, bubbles, cancelable) {
             if ("CustomEvent" in window && typeof window.CustomEvent === "function") return new CustomEvent(eventType, {
                 bubbles: bubbles,
                 cancelable: cancelable
             });
             else {
                 var ev = document.createEvent("Events");
                 ev.initEvent(eventType, bubbles, cancelable);
                 return ev
             }
         }
 
         function upgradeDomInternal(optJsClass,
             optCssClass) {
             if (typeof optJsClass === "undefined" && typeof optCssClass === "undefined")
                 for (var i = 0; i < registeredComponents_.length; i++) upgradeDomInternal(registeredComponents_[i].className, registeredComponents_[i].cssClass);
             else {
                 var jsClass = (optJsClass);
                 if (typeof optCssClass === "undefined") {
                     var registeredClass = findRegisteredClass_(jsClass);
                     if (registeredClass) optCssClass = registeredClass.cssClass
                 }
                 var elements = document.querySelectorAll("." + optCssClass);
                 for (var n = 0; n < elements.length; n++) upgradeElementInternal(elements[n],
                     jsClass)
             }
         }
 
         function upgradeElementInternal(element, optJsClass) {
             if (!(typeof element === "object" && element instanceof Element)) throw new Error("Invalid argument provided to upgrade MDL element.");
             var upgradingEv = createEvent_("mdl-componentupgrading", true, true);
             element.dispatchEvent(upgradingEv);
             if (upgradingEv.defaultPrevented) return;
             var upgradedList = getUpgradedListOfElement_(element);
             var classesToUpgrade = [];
             if (!optJsClass) {
                 var classList = element.classList;
                 registeredComponents_.forEach(function(component) {
                     if (classList.contains(component.cssClass) &&
                         classesToUpgrade.indexOf(component) === -1 && !isElementUpgraded_(element, component.className)) classesToUpgrade.push(component)
                 })
             } else if (!isElementUpgraded_(element, optJsClass)) classesToUpgrade.push(findRegisteredClass_(optJsClass));
             for (var i = 0, n = classesToUpgrade.length, registeredClass; i < n; i++) {
                 registeredClass = classesToUpgrade[i];
                 if (registeredClass) {
                     upgradedList.push(registeredClass.className);
                     element.setAttribute("data-upgraded", upgradedList.join(","));
                     var instance = new registeredClass.classConstructor(element);
                     instance[componentConfigProperty_] = registeredClass;
                     createdComponents_.push(instance);
                     for (var j = 0, m = registeredClass.callbacks.length; j < m; j++) registeredClass.callbacks[j](element);
                     if (registeredClass.widget) element[registeredClass.className] = instance
                 } else throw new Error("Unable to find a registered component for the given class.");
                 var upgradedEv = createEvent_("mdl-componentupgraded", true, false);
                 element.dispatchEvent(upgradedEv)
             }
         }
 
         function upgradeElementsInternal(elements) {
             if (!Array.isArray(elements))
                 if (elements instanceof Element) elements = [elements];
                 else elements = Array.prototype.slice.call(elements);
             for (var i = 0, n = elements.length, element; i < n; i++) {
                 element = elements[i];
                 if (element instanceof HTMLElement) {
                     upgradeElementInternal(element);
                     if (element.children.length > 0) upgradeElementsInternal(element.children)
                 }
             }
         }
 
         function registerInternal(config) {
             var widgetMissing = typeof config.widget === "undefined" && typeof config["widget"] === "undefined";
             var widget = true;
             if (!widgetMissing) widget = config.widget || config["widget"];
             var newConfig = ({
                 classConstructor: config.constructor ||
                     config["constructor"],
                 className: config.classAsString || config["classAsString"],
                 cssClass: config.cssClass || config["cssClass"],
                 widget: widget,
                 callbacks: []
             });
             registeredComponents_.forEach(function(item) {
                 if (item.cssClass === newConfig.cssClass) throw new Error("The provided cssClass has already been registered: " + item.cssClass);
                 if (item.className === newConfig.className) throw new Error("The provided className has already been registered");
             });
             if (config.constructor.prototype.hasOwnProperty(componentConfigProperty_)) throw new Error("MDL component classes must not have " +
                 componentConfigProperty_ + " defined as a property.");
             var found = findRegisteredClass_(config.classAsString, newConfig);
             if (!found) registeredComponents_.push(newConfig)
         }
 
         function registerUpgradedCallbackInternal(jsClass, callback) {
             var regClass = findRegisteredClass_(jsClass);
             if (regClass) regClass.callbacks.push(callback)
         }
 
         function upgradeAllRegisteredInternal() {
             for (var n = 0; n < registeredComponents_.length; n++) upgradeDomInternal(registeredComponents_[n].className)
         }
 
         function deconstructComponentInternal(component) {
             if (component) {
                 var componentIndex =
                     createdComponents_.indexOf(component);
                 createdComponents_.splice(componentIndex, 1);
                 var upgrades = component.element_.getAttribute("data-upgraded").split(",");
                 var componentPlace = upgrades.indexOf(component[componentConfigProperty_].classAsString);
                 upgrades.splice(componentPlace, 1);
                 component.element_.setAttribute("data-upgraded", upgrades.join(","));
                 var ev = createEvent_("mdl-componentdowngraded", true, false);
                 component.element_.dispatchEvent(ev)
             }
         }
 
         function downgradeNodesInternal(nodes) {
             var downgradeNode = function(node) {
                 createdComponents_.filter(function(item) {
                     return item.element_ ===
                         node
                 }).forEach(deconstructComponentInternal)
             };
             if (nodes instanceof Array || nodes instanceof NodeList)
                 for (var n = 0; n < nodes.length; n++) downgradeNode(nodes[n]);
             else if (nodes instanceof Node) downgradeNode(nodes);
             else throw new Error("Invalid argument provided to downgrade MDL nodes.");
         }
         return {
             upgradeDom: upgradeDomInternal,
             upgradeElement: upgradeElementInternal,
             upgradeElements: upgradeElementsInternal,
             upgradeAllRegistered: upgradeAllRegisteredInternal,
             registerUpgradedCallback: registerUpgradedCallbackInternal,
             register: registerInternal,
             downgradeElements: downgradeNodesInternal
         }
     }();
     componentHandler.ComponentConfigPublic;
     componentHandler.ComponentConfig;
     componentHandler.Component;
     componentHandler["upgradeDom"] = componentHandler.upgradeDom;
     componentHandler["upgradeElement"] = componentHandler.upgradeElement;
     componentHandler["upgradeElements"] = componentHandler.upgradeElements;
     componentHandler["upgradeAllRegistered"] = componentHandler.upgradeAllRegistered;
     componentHandler["registerUpgradedCallback"] = componentHandler.registerUpgradedCallback;
     componentHandler["register"] = componentHandler.register;
     componentHandler["downgradeElements"] = componentHandler.downgradeElements;
     window.componentHandler = componentHandler;
     window["componentHandler"] = componentHandler;
     window.addEventListener("load", function() {
         if ("classList" in document.createElement("div") && "querySelector" in document && "addEventListener" in window && Array.prototype.forEach) {
             document.documentElement.classList.add("mdl-js");
             componentHandler.upgradeAllRegistered()
         } else {
             componentHandler.upgradeElement = function() {};
             componentHandler.register = function() {}
         }
     });
     (function() {
         var MaterialButton = function MaterialButton(element) {
             this.element_ = element;
             this.init()
         };
         window["MaterialButton"] = MaterialButton;
         MaterialButton.prototype.Constant_ = {};
         MaterialButton.prototype.CssClasses_ = {
             RIPPLE_EFFECT: "mdl-js-ripple-effect",
             RIPPLE_CONTAINER: "mdl-button__ripple-container",
             RIPPLE: "mdl-ripple"
         };
         MaterialButton.prototype.blurHandler_ = function(event) {
             if (event) this.element_.blur()
         };
         MaterialButton.prototype.disable = function() {
             this.element_.disabled = true
         };
         MaterialButton.prototype["disable"] =
             MaterialButton.prototype.disable;
         MaterialButton.prototype.enable = function() {
             this.element_.disabled = false
         };
         MaterialButton.prototype["enable"] = MaterialButton.prototype.enable;
         MaterialButton.prototype.init = function() {
             if (this.element_) {
                 if (this.element_.classList.contains(this.CssClasses_.RIPPLE_EFFECT)) {
                     var rippleContainer = document.createElement("span");
                     rippleContainer.classList.add(this.CssClasses_.RIPPLE_CONTAINER);
                     this.rippleElement_ = document.createElement("span");
                     this.rippleElement_.classList.add(this.CssClasses_.RIPPLE);
                     rippleContainer.appendChild(this.rippleElement_);
                     this.boundRippleBlurHandler = this.blurHandler_.bind(this);
                     this.rippleElement_.addEventListener("mouseup", this.boundRippleBlurHandler);
                     this.element_.appendChild(rippleContainer)
                 }
                 this.boundButtonBlurHandler = this.blurHandler_.bind(this);
                 this.element_.addEventListener("mouseup", this.boundButtonBlurHandler);
                 this.element_.addEventListener("mouseleave", this.boundButtonBlurHandler)
             }
         };
         componentHandler.register({
             constructor: MaterialButton,
             classAsString: "MaterialButton",
             cssClass: "mdl-js-button",
             widget: true
         })
     })();
     (function() {
         var MaterialProgress = function MaterialProgress(element) {
             this.element_ = element;
             this.init()
         };
         window["MaterialProgress"] = MaterialProgress;
         MaterialProgress.prototype.Constant_ = {};
         MaterialProgress.prototype.CssClasses_ = {
             INDETERMINATE_CLASS: "mdl-progress__indeterminate"
         };
         MaterialProgress.prototype.setProgress = function(p) {
             if (this.element_.classList.contains(this.CssClasses_.INDETERMINATE_CLASS)) return;
             this.progressbar_.style.width = p + "%"
         };
         MaterialProgress.prototype["setProgress"] = MaterialProgress.prototype.setProgress;
         MaterialProgress.prototype.setBuffer = function(p) {
             this.bufferbar_.style.width = p + "%";
             this.auxbar_.style.width = 100 - p + "%"
         };
         MaterialProgress.prototype["setBuffer"] = MaterialProgress.prototype.setBuffer;
         MaterialProgress.prototype.init = function() {
             if (this.element_) {
                 var el = document.createElement("div");
                 el.className = "progressbar bar bar1";
                 this.element_.appendChild(el);
                 this.progressbar_ = el;
                 el = document.createElement("div");
                 el.className = "bufferbar bar bar2";
                 this.element_.appendChild(el);
                 this.bufferbar_ = el;
                 el = document.createElement("div");
                 el.className = "auxbar bar bar3";
                 this.element_.appendChild(el);
                 this.auxbar_ = el;
                 this.progressbar_.style.width = "0%";
                 this.bufferbar_.style.width = "100%";
                 this.auxbar_.style.width = "0%";
                 this.element_.classList.add("is-upgraded")
             }
         };
         componentHandler.register({
             constructor: MaterialProgress,
             classAsString: "MaterialProgress",
             cssClass: "mdl-js-progress",
             widget: true
         })
     })();
     (function() {
         var MaterialSpinner = function MaterialSpinner(element) {
             this.element_ = element;
             this.init()
         };
         window["MaterialSpinner"] = MaterialSpinner;
         MaterialSpinner.prototype.Constant_ = {
             MDL_SPINNER_LAYER_COUNT: 4
         };
         MaterialSpinner.prototype.CssClasses_ = {
             MDL_SPINNER_LAYER: "mdl-spinner__layer",
             MDL_SPINNER_CIRCLE_CLIPPER: "mdl-spinner__circle-clipper",
             MDL_SPINNER_CIRCLE: "mdl-spinner__circle",
             MDL_SPINNER_GAP_PATCH: "mdl-spinner__gap-patch",
             MDL_SPINNER_LEFT: "mdl-spinner__left",
             MDL_SPINNER_RIGHT: "mdl-spinner__right"
         };
         MaterialSpinner.prototype.createLayer = function(index) {
             var layer = document.createElement("div");
             layer.classList.add(this.CssClasses_.MDL_SPINNER_LAYER);
             layer.classList.add(this.CssClasses_.MDL_SPINNER_LAYER + "-" + index);
             var leftClipper = document.createElement("div");
             leftClipper.classList.add(this.CssClasses_.MDL_SPINNER_CIRCLE_CLIPPER);
             leftClipper.classList.add(this.CssClasses_.MDL_SPINNER_LEFT);
             var gapPatch = document.createElement("div");
             gapPatch.classList.add(this.CssClasses_.MDL_SPINNER_GAP_PATCH);
             var rightClipper =
                 document.createElement("div");
             rightClipper.classList.add(this.CssClasses_.MDL_SPINNER_CIRCLE_CLIPPER);
             rightClipper.classList.add(this.CssClasses_.MDL_SPINNER_RIGHT);
             var circleOwners = [leftClipper, gapPatch, rightClipper];
             for (var i = 0; i < circleOwners.length; i++) {
                 var circle = document.createElement("div");
                 circle.classList.add(this.CssClasses_.MDL_SPINNER_CIRCLE);
                 circleOwners[i].appendChild(circle)
             }
             layer.appendChild(leftClipper);
             layer.appendChild(gapPatch);
             layer.appendChild(rightClipper);
             this.element_.appendChild(layer)
         };
         MaterialSpinner.prototype["createLayer"] = MaterialSpinner.prototype.createLayer;
         MaterialSpinner.prototype.stop = function() {
             this.element_.classList.remove("is-active")
         };
         MaterialSpinner.prototype["stop"] = MaterialSpinner.prototype.stop;
         MaterialSpinner.prototype.start = function() {
             this.element_.classList.add("is-active")
         };
         MaterialSpinner.prototype["start"] = MaterialSpinner.prototype.start;
         MaterialSpinner.prototype.init = function() {
             if (this.element_) {
                 for (var i = 1; i <= this.Constant_.MDL_SPINNER_LAYER_COUNT; i++) this.createLayer(i);
                 this.element_.classList.add("is-upgraded")
             }
         };
         componentHandler.register({
             constructor: MaterialSpinner,
             classAsString: "MaterialSpinner",
             cssClass: "mdl-js-spinner",
             widget: true
         })
     })();
     (function() {
         var MaterialTextfield = function MaterialTextfield(element) {
             this.element_ = element;
             this.maxRows = this.Constant_.NO_MAX_ROWS;
             this.init()
         };
         window["MaterialTextfield"] = MaterialTextfield;
         MaterialTextfield.prototype.Constant_ = {
             NO_MAX_ROWS: -1,
             MAX_ROWS_ATTRIBUTE: "maxrows"
         };
         MaterialTextfield.prototype.CssClasses_ = {
             LABEL: "mdl-textfield__label",
             INPUT: "mdl-textfield__input",
             IS_DIRTY: "is-dirty",
             IS_FOCUSED: "is-focused",
             IS_DISABLED: "is-disabled",
             IS_INVALID: "is-invalid",
             IS_UPGRADED: "is-upgraded",
             HAS_PLACEHOLDER: "has-placeholder"
         };
         MaterialTextfield.prototype.onKeyDown_ = function(event) {
             var currentRowCount = event.target.value.split("\n").length;
             if (event.keyCode === 13)
                 if (currentRowCount >= this.maxRows) event.preventDefault()
         };
         MaterialTextfield.prototype.onFocus_ = function(event) {
             this.element_.classList.add(this.CssClasses_.IS_FOCUSED)
         };
         MaterialTextfield.prototype.onBlur_ = function(event) {
             this.element_.classList.remove(this.CssClasses_.IS_FOCUSED)
         };
         MaterialTextfield.prototype.onReset_ = function(event) {
             this.updateClasses_()
         };
         MaterialTextfield.prototype.updateClasses_ =
             function() {
                 this.checkDisabled();
                 this.checkValidity();
                 this.checkDirty();
                 this.checkFocus()
             };
         MaterialTextfield.prototype.checkDisabled = function() {
             if (this.input_.disabled) this.element_.classList.add(this.CssClasses_.IS_DISABLED);
             else this.element_.classList.remove(this.CssClasses_.IS_DISABLED)
         };
         MaterialTextfield.prototype["checkDisabled"] = MaterialTextfield.prototype.checkDisabled;
         MaterialTextfield.prototype.checkFocus = function() {
             if (Boolean(this.element_.querySelector(":focus"))) this.element_.classList.add(this.CssClasses_.IS_FOCUSED);
             else this.element_.classList.remove(this.CssClasses_.IS_FOCUSED)
         };
         MaterialTextfield.prototype["checkFocus"] = MaterialTextfield.prototype.checkFocus;
         MaterialTextfield.prototype.checkValidity = function() {
             if (this.input_.validity)
                 if (this.input_.validity.valid) this.element_.classList.remove(this.CssClasses_.IS_INVALID);
                 else this.element_.classList.add(this.CssClasses_.IS_INVALID)
         };
         MaterialTextfield.prototype["checkValidity"] = MaterialTextfield.prototype.checkValidity;
         MaterialTextfield.prototype.checkDirty =
             function() {
                 if (this.input_.value && this.input_.value.length > 0) this.element_.classList.add(this.CssClasses_.IS_DIRTY);
                 else this.element_.classList.remove(this.CssClasses_.IS_DIRTY)
             };
         MaterialTextfield.prototype["checkDirty"] = MaterialTextfield.prototype.checkDirty;
         MaterialTextfield.prototype.disable = function() {
             this.input_.disabled = true;
             this.updateClasses_()
         };
         MaterialTextfield.prototype["disable"] = MaterialTextfield.prototype.disable;
         MaterialTextfield.prototype.enable = function() {
             this.input_.disabled = false;
             this.updateClasses_()
         };
         MaterialTextfield.prototype["enable"] = MaterialTextfield.prototype.enable;
         MaterialTextfield.prototype.change = function(value) {
             this.input_.value = value || "";
             this.updateClasses_()
         };
         MaterialTextfield.prototype["change"] = MaterialTextfield.prototype.change;
         MaterialTextfield.prototype.init = function() {
             if (this.element_) {
                 this.label_ = this.element_.querySelector("." + this.CssClasses_.LABEL);
                 this.input_ = this.element_.querySelector("." + this.CssClasses_.INPUT);
                 if (this.input_) {
                     if (this.input_.hasAttribute((this.Constant_.MAX_ROWS_ATTRIBUTE))) {
                         this.maxRows =
                             parseInt(this.input_.getAttribute((this.Constant_.MAX_ROWS_ATTRIBUTE)), 10);
                         if (isNaN(this.maxRows)) this.maxRows = this.Constant_.NO_MAX_ROWS
                     }
                     if (this.input_.hasAttribute("placeholder")) this.element_.classList.add(this.CssClasses_.HAS_PLACEHOLDER);
                     this.boundUpdateClassesHandler = this.updateClasses_.bind(this);
                     this.boundFocusHandler = this.onFocus_.bind(this);
                     this.boundBlurHandler = this.onBlur_.bind(this);
                     this.boundResetHandler = this.onReset_.bind(this);
                     this.input_.addEventListener("input", this.boundUpdateClassesHandler);
                     this.input_.addEventListener("focus", this.boundFocusHandler);
                     this.input_.addEventListener("blur", this.boundBlurHandler);
                     this.input_.addEventListener("reset", this.boundResetHandler);
                     if (this.maxRows !== this.Constant_.NO_MAX_ROWS) {
                         this.boundKeyDownHandler = this.onKeyDown_.bind(this);
                         this.input_.addEventListener("keydown", this.boundKeyDownHandler)
                     }
                     var invalid = this.element_.classList.contains(this.CssClasses_.IS_INVALID);
                     this.updateClasses_();
                     this.element_.classList.add(this.CssClasses_.IS_UPGRADED);
                     if (invalid) this.element_.classList.add(this.CssClasses_.IS_INVALID);
                     if (this.input_.hasAttribute("autofocus")) {
                         this.element_.focus();
                         this.checkFocus()
                     }
                 }
             }
         };
         componentHandler.register({
             constructor: MaterialTextfield,
             classAsString: "MaterialTextfield",
             cssClass: "mdl-js-textfield",
             widget: true
         })
     })();
     (function() {
         var supportCustomEvent = window.CustomEvent;
         if (!supportCustomEvent || typeof supportCustomEvent == "object") {
             supportCustomEvent = function CustomEvent(event, x) {
                 x = x || {};
                 var ev = document.createEvent("CustomEvent");
                 ev.initCustomEvent(event, !!x.bubbles, !!x.cancelable, x.detail || null);
                 return ev
             };
             supportCustomEvent.prototype = window.Event.prototype
         }
 
         function createsStackingContext(el) {
             while (el && el !== document.body) {
                 var s = window.getComputedStyle(el);
                 var invalid = function(k, ok) {
                     return !(s[k] === undefined || s[k] ===
                         ok)
                 };
                 if (s.opacity < 1 || invalid("zIndex", "auto") || invalid("transform", "none") || invalid("mixBlendMode", "normal") || invalid("filter", "none") || invalid("perspective", "none") || s["isolation"] === "isolate" || s.position === "fixed" || s.webkitOverflowScrolling === "touch") return true;
                 el = el.parentElement
             }
             return false
         }
 
         function findNearestDialog(el) {
             while (el) {
                 if (el.localName === "dialog") return (el);
                 el = el.parentElement
             }
             return null
         }
 
         function safeBlur(el) {
             if (el && el.blur && el != document.body) el.blur()
         }
 
         function inNodeList(nodeList,
             node) {
             for (var i = 0; i < nodeList.length; ++i)
                 if (nodeList[i] == node) return true;
             return false
         }
 
         function dialogPolyfillInfo(dialog) {
             this.dialog_ = dialog;
             this.replacedStyleTop_ = false;
             this.openAsModal_ = false;
             if (!dialog.hasAttribute("role")) dialog.setAttribute("role", "dialog");
             dialog.show = this.show.bind(this);
             dialog.showModal = this.showModal.bind(this);
             dialog.close = this.close.bind(this);
             if (!("returnValue" in dialog)) dialog.returnValue = "";
             if ("MutationObserver" in window) {
                 var mo = new MutationObserver(this.maybeHideModal.bind(this));
                 mo.observe(dialog, {
                     attributes: true,
                     attributeFilter: ["open"]
                 })
             } else {
                 var removed = false;
                 var cb = function() {
                     removed ? this.downgradeModal() : this.maybeHideModal();
                     removed = false
                 }.bind(this);
                 var timeout;
                 var delayModel = function(ev) {
                     var cand = "DOMNodeRemoved";
                     removed |= ev.type.substr(0, cand.length) === cand;
                     window.clearTimeout(timeout);
                     timeout = window.setTimeout(cb, 0)
                 };
                 ["DOMAttrModified", "DOMNodeRemoved", "DOMNodeRemovedFromDocument"].forEach(function(name) {
                     dialog.addEventListener(name, delayModel)
                 })
             }
             Object.defineProperty(dialog,
                 "open", {
                     set: this.setOpen.bind(this),
                     get: dialog.hasAttribute.bind(dialog, "open")
                 });
             this.backdrop_ = document.createElement("div");
             this.backdrop_.className = "backdrop";
             this.backdrop_.addEventListener("click", this.backdropClick_.bind(this))
         }
         dialogPolyfillInfo.prototype = {
             get dialog() {
                 return this.dialog_
             },
             maybeHideModal: function() {
                 if (this.dialog_.hasAttribute("open") && document.body.contains(this.dialog_)) return;
                 this.downgradeModal()
             },
             downgradeModal: function() {
                 if (!this.openAsModal_) return;
                 this.openAsModal_ =
                     false;
                 this.dialog_.style.zIndex = "";
                 if (this.replacedStyleTop_) {
                     this.dialog_.style.top = "";
                     this.replacedStyleTop_ = false
                 }
                 this.backdrop_.parentNode && this.backdrop_.parentNode.removeChild(this.backdrop_);
                 dialogPolyfill.dm.removeDialog(this)
             },
             setOpen: function(value) {
                 if (value) this.dialog_.hasAttribute("open") || this.dialog_.setAttribute("open", "");
                 else {
                     this.dialog_.removeAttribute("open");
                     this.maybeHideModal()
                 }
             },
             backdropClick_: function(e) {
                 if (!this.dialog_.hasAttribute("tabindex")) {
                     var fake = document.createElement("div");
                     this.dialog_.insertBefore(fake, this.dialog_.firstChild);
                     fake.tabIndex = -1;
                     fake.focus();
                     this.dialog_.removeChild(fake)
                 } else this.dialog_.focus();
                 var redirectedEvent = document.createEvent("MouseEvents");
                 redirectedEvent.initMouseEvent(e.type, e.bubbles, e.cancelable, window, e.detail, e.screenX, e.screenY, e.clientX, e.clientY, e.ctrlKey, e.altKey, e.shiftKey, e.metaKey, e.button, e.relatedTarget);
                 this.dialog_.dispatchEvent(redirectedEvent);
                 e.stopPropagation()
             },
             focus_: function() {
                 var target = this.dialog_.querySelector("[autofocus]:not([disabled])");
                 if (!target && this.dialog_.tabIndex >= 0) target = this.dialog_;
                 if (!target) {
                     var opts = ["button", "input", "keygen", "select", "textarea"];
                     var query = opts.map(function(el) {
                         return el + ":not([disabled])"
                     });
                     query.push('[tabindex]:not([disabled]):not([tabindex=""])');
                     target = this.dialog_.querySelector(query.join(", "))
                 }
                 safeBlur(document.activeElement);
                 target && target.focus()
             },
             updateZIndex: function(dialogZ, backdropZ) {
                 if (dialogZ < backdropZ) throw new Error("dialogZ should never be < backdropZ");
                 this.dialog_.style.zIndex =
                     dialogZ;
                 this.backdrop_.style.zIndex = backdropZ
             },
             show: function() {
                 if (!this.dialog_.open) {
                     this.setOpen(true);
                     this.focus_()
                 }
             },
             showModal: function() {
                 if (this.dialog_.hasAttribute("open")) throw new Error("Failed to execute 'showModal' on dialog: The element is already open, and therefore cannot be opened modally.");
                 if (!document.body.contains(this.dialog_)) throw new Error("Failed to execute 'showModal' on dialog: The element is not in a Document.");
                 if (!dialogPolyfill.dm.pushDialog(this)) throw new Error("Failed to execute 'showModal' on dialog: There are too many open modal dialogs.");
                 if (createsStackingContext(this.dialog_.parentElement)) console.warn("A dialog is being shown inside a stacking context. " + "This may cause it to be unusable. For more information, see this link: " + "https://github.com/GoogleChrome/dialog-polyfill/#stacking-context");
                 this.setOpen(true);
                 this.openAsModal_ = true;
                 if (dialogPolyfill.needsCentering(this.dialog_)) {
                     dialogPolyfill.reposition(this.dialog_);
                     this.replacedStyleTop_ = true
                 } else this.replacedStyleTop_ = false;
                 this.dialog_.parentNode.insertBefore(this.backdrop_,
                     this.dialog_.nextSibling);
                 this.focus_()
             },
             close: function(opt_returnValue) {
                 if (!this.dialog_.hasAttribute("open")) throw new Error("Failed to execute 'close' on dialog: The element does not have an 'open' attribute, and therefore cannot be closed.");
                 this.setOpen(false);
                 if (opt_returnValue !== undefined) this.dialog_.returnValue = opt_returnValue;
                 var closeEvent = new supportCustomEvent("close", {
                     bubbles: false,
                     cancelable: false
                 });
                 this.dialog_.dispatchEvent(closeEvent)
             }
         };
         var dialogPolyfill = {};
         dialogPolyfill.reposition =
             function(element) {
                 var scrollTop = document.body.scrollTop || document.documentElement.scrollTop;
                 var topValue = scrollTop + (window.innerHeight - element.offsetHeight) / 2;
                 element.style.top = Math.max(scrollTop, topValue) + "px"
             };
         dialogPolyfill.isInlinePositionSetByStylesheet = function(element) {
             for (var i = 0; i < document.styleSheets.length; ++i) {
                 var styleSheet = document.styleSheets[i];
                 var cssRules = null;
                 try {
                     cssRules = styleSheet.cssRules
                 } catch (e) {}
                 if (!cssRules) continue;
                 for (var j = 0; j < cssRules.length; ++j) {
                     var rule = cssRules[j];
                     var selectedNodes = null;
                     try {
                         selectedNodes = document.querySelectorAll(rule.selectorText)
                     } catch (e$0) {}
                     if (!selectedNodes || !inNodeList(selectedNodes, element)) continue;
                     var cssTop = rule.style.getPropertyValue("top");
                     var cssBottom = rule.style.getPropertyValue("bottom");
                     if (cssTop && cssTop != "auto" || cssBottom && cssBottom != "auto") return true
                 }
             }
             return false
         };
         dialogPolyfill.needsCentering = function(dialog) {
             var computedStyle = window.getComputedStyle(dialog);
             if (computedStyle.position != "absolute") return false;
             if (dialog.style.top !=
                 "auto" && dialog.style.top != "" || dialog.style.bottom != "auto" && dialog.style.bottom != "") return false;
             return !dialogPolyfill.isInlinePositionSetByStylesheet(dialog)
         };
         dialogPolyfill.forceRegisterDialog = function(element) {
             if (element.showModal) console.warn("This browser already supports <dialog>, the polyfill " + "may not work correctly", element);
             if (element.localName !== "dialog") throw new Error("Failed to register dialog: The element is not a dialog.");
             new dialogPolyfillInfo((element))
         };
         dialogPolyfill.registerDialog =
             function(element) {
                 if (!element.showModal) dialogPolyfill.forceRegisterDialog(element)
             };
         dialogPolyfill.DialogManager = function() {
             this.pendingDialogStack = [];
             var checkDOM = this.checkDOM_.bind(this);
             this.overlay = document.createElement("div");
             this.overlay.className = "_dialog_overlay";
             this.overlay.addEventListener("click", function(e) {
                 this.forwardTab_ = undefined;
                 e.stopPropagation();
                 checkDOM([])
             }.bind(this));
             this.handleKey_ = this.handleKey_.bind(this);
             this.handleFocus_ = this.handleFocus_.bind(this);
             this.zIndexLow_ =
                 1E5;
             this.zIndexHigh_ = 1E5 + 150;
             this.forwardTab_ = undefined;
             if ("MutationObserver" in window) this.mo_ = new MutationObserver(function(records) {
                 var removed = [];
                 records.forEach(function(rec) {
                     for (var i = 0, c; c = rec.removedNodes[i]; ++i)
                         if (!(c instanceof Element)) continue;
                         else if (c.localName === "dialog") removed.push(c);
                     else {
                         var q = c.querySelector("dialog");
                         q && removed.push(q)
                     }
                 });
                 removed.length && checkDOM(removed)
             })
         };
         dialogPolyfill.DialogManager.prototype.blockDocument = function() {
             document.documentElement.addEventListener("focus",
                 this.handleFocus_, true);
             document.addEventListener("keydown", this.handleKey_);
             this.mo_ && this.mo_.observe(document, {
                 childList: true,
                 subtree: true
             })
         };
         dialogPolyfill.DialogManager.prototype.unblockDocument = function() {
             document.documentElement.removeEventListener("focus", this.handleFocus_, true);
             document.removeEventListener("keydown", this.handleKey_);
             this.mo_ && this.mo_.disconnect()
         };
         dialogPolyfill.DialogManager.prototype.updateStacking = function() {
             var zIndex = this.zIndexHigh_;
             for (var i = 0, dpi; dpi = this.pendingDialogStack[i]; ++i) {
                 dpi.updateZIndex(--zIndex,
                     --zIndex);
                 if (i === 0) this.overlay.style.zIndex = --zIndex
             }
             var last = this.pendingDialogStack[0];
             if (last) {
                 var p = last.dialog.parentNode || document.body;
                 p.appendChild(this.overlay)
             } else if (this.overlay.parentNode) this.overlay.parentNode.removeChild(this.overlay)
         };
         dialogPolyfill.DialogManager.prototype.containedByTopDialog_ = function(candidate) {
             while (candidate = findNearestDialog(candidate)) {
                 for (var i = 0, dpi; dpi = this.pendingDialogStack[i]; ++i)
                     if (dpi.dialog === candidate) return i === 0;
                 candidate = candidate.parentElement
             }
             return false
         };
         dialogPolyfill.DialogManager.prototype.handleFocus_ = function(event) {
             if (this.containedByTopDialog_(event.target)) return;
             event.preventDefault();
             event.stopPropagation();
             safeBlur((event.target));
             if (this.forwardTab_ === undefined) return;
             var dpi = this.pendingDialogStack[0];
             var dialog = dpi.dialog;
             var position = dialog.compareDocumentPosition(event.target);
             if (position & Node.DOCUMENT_POSITION_PRECEDING)
                 if (this.forwardTab_) dpi.focus_();
                 else document.documentElement.focus();
             else;
             return false
         };
         dialogPolyfill.DialogManager.prototype.handleKey_ =
             function(event) {
                 this.forwardTab_ = undefined;
                 if (event.keyCode === 27) {
                     event.preventDefault();
                     event.stopPropagation();
                     var cancelEvent = new supportCustomEvent("cancel", {
                         bubbles: false,
                         cancelable: true
                     });
                     var dpi = this.pendingDialogStack[0];
                     if (dpi && dpi.dialog.dispatchEvent(cancelEvent)) dpi.dialog.close()
                 } else if (event.keyCode === 9) this.forwardTab_ = !event.shiftKey
             };
         dialogPolyfill.DialogManager.prototype.checkDOM_ = function(removed) {
             var clone = this.pendingDialogStack.slice();
             clone.forEach(function(dpi) {
                 if (removed.indexOf(dpi.dialog) !==
                     -1) dpi.downgradeModal();
                 else dpi.maybeHideModal()
             })
         };
         dialogPolyfill.DialogManager.prototype.pushDialog = function(dpi) {
             var allowed = (this.zIndexHigh_ - this.zIndexLow_) / 2 - 1;
             if (this.pendingDialogStack.length >= allowed) return false;
             if (this.pendingDialogStack.unshift(dpi) === 1) this.blockDocument();
             this.updateStacking();
             return true
         };
         dialogPolyfill.DialogManager.prototype.removeDialog = function(dpi) {
             var index = this.pendingDialogStack.indexOf(dpi);
             if (index == -1) return;
             this.pendingDialogStack.splice(index, 1);
             if (this.pendingDialogStack.length ===
                 0) this.unblockDocument();
             this.updateStacking()
         };
         dialogPolyfill.dm = new dialogPolyfill.DialogManager;
         document.addEventListener("submit", function(ev) {
             var target = ev.target;
             if (!target || !target.hasAttribute("method")) return;
             if (target.getAttribute("method").toLowerCase() !== "dialog") return;
             ev.preventDefault();
             var dialog = findNearestDialog((ev.target));
             if (!dialog) return;
             var returnValue;
             var cands = [document.activeElement, ev.explicitOriginalTarget];
             var els = ["BUTTON", "INPUT"];
             cands.some(function(cand) {
                 if (cand &&
                     cand.form == ev.target && els.indexOf(cand.nodeName.toUpperCase()) != -1) {
                     returnValue = cand.value;
                     return true
                 }
             });
             dialog.close(returnValue)
         }, true);
         dialogPolyfill["forceRegisterDialog"] = dialogPolyfill.forceRegisterDialog;
         dialogPolyfill["registerDialog"] = dialogPolyfill.registerDialog;
         if (typeof define === "function" && "amd" in define) define(function() {
             return dialogPolyfill
         });
         else if (typeof module === "object" && typeof module["exports"] === "object") module["exports"] = dialogPolyfill;
         else window["dialogPolyfill"] = dialogPolyfill
     })();
     (function() {
         var h, l = this;
 
         function m(a) {
             return void 0 !== a
         }
 
         function aa() {}
 
         function ba(a) {
             var b = typeof a;
             if ("object" == b)
                 if (a) {
                     if (a instanceof Array) return "array";
                     if (a instanceof Object) return b;
                     var c = Object.prototype.toString.call(a);
                     if ("[object Window]" == c) return "object";
                     if ("[object Array]" == c || "number" == typeof a.length && "undefined" != typeof a.splice && "undefined" != typeof a.propertyIsEnumerable && !a.propertyIsEnumerable("splice")) return "array";
                     if ("[object Function]" == c || "undefined" != typeof a.call && "undefined" !=
                         typeof a.propertyIsEnumerable && !a.propertyIsEnumerable("call")) return "function"
                 } else return "null";
             else if ("function" == b && "undefined" == typeof a.call) return "object";
             return b
         }
 
         function ca(a) {
             return null != a
         }
 
         function da(a) {
             return "array" == ba(a)
         }
 
         function ea(a) {
             var b = ba(a);
             return "array" == b || "object" == b && "number" == typeof a.length
         }
 
         function n(a) {
             return "string" == typeof a
         }
 
         function fa(a) {
             return "number" == typeof a
         }
 
         function ga(a) {
             return "function" == ba(a)
         }
 
         function ha(a) {
             var b = typeof a;
             return "object" == b && null != a || "function" ==
                 b
         }
         var ia = "closure_uid_" + (1E9 * Math.random() >>> 0),
             ja = 0;
 
         function ka(a, b, c) {
             return a.call.apply(a.bind, arguments)
         }
 
         function la(a, b, c) {
             if (!a) throw Error();
             if (2 < arguments.length) {
                 var d = Array.prototype.slice.call(arguments, 2);
                 return function() {
                     var c = Array.prototype.slice.call(arguments);
                     Array.prototype.unshift.apply(c, d);
                     return a.apply(b, c)
                 }
             }
             return function() {
                 return a.apply(b, arguments)
             }
         }
 
         function p(a, b, c) {
             p = Function.prototype.bind && -1 != Function.prototype.bind.toString().indexOf("native code") ? ka : la;
             return p.apply(null,
                 arguments)
         }
 
         function ma(a, b) {
             var c = Array.prototype.slice.call(arguments, 1);
             return function() {
                 var b = c.slice();
                 b.push.apply(b, arguments);
                 return a.apply(this, b)
             }
         }
 
         function q(a, b) {
             for (var c in b) a[c] = b[c]
         }
         var na = Date.now || function() {
             return +new Date
         };
 
         function oa(a, b) {
             a = a.split(".");
             var c = l;
             a[0] in c || !c.execScript || c.execScript("var " + a[0]);
             for (var d; a.length && (d = a.shift());) !a.length && m(b) ? c[d] = b : c = c[d] && c[d] !== Object.prototype[d] ? c[d] : c[d] = {}
         }
 
         function r(a, b) {
             function c() {}
             c.prototype = b.prototype;
             a.h = b.prototype;
             a.prototype = new c;
             a.prototype.constructor = a;
             a.Oe = function(a, c, f) {
                 for (var g = Array(arguments.length - 2), k = 2; k < arguments.length; k++) g[k - 2] = arguments[k];
                 return b.prototype[c].apply(a, g)
             }
         }
 
         function pa(a) {
             if (Error.captureStackTrace) Error.captureStackTrace(this, pa);
             else {
                 var b = Error().stack;
                 b && (this.stack = b)
             }
             a && (this.message = String(a))
         }
         r(pa, Error);
         pa.prototype.name = "CustomError";
         var qa;
 
         function ra(a, b) {
             for (var c = a.split("%s"), d = "", e = Array.prototype.slice.call(arguments, 1); e.length && 1 < c.length;) d += c.shift() +
                 e.shift();
             return d + c.join("%s")
         }
         var sa = String.prototype.trim ? function(a) {
             return a.trim()
         } : function(a) {
             return a.replace(/^[\s\xa0]+|[\s\xa0]+$/g, "")
         };
 
         function ta(a) {
             if (!ua.test(a)) return a; - 1 != a.indexOf("&") && (a = a.replace(va, "&amp;")); - 1 != a.indexOf("<") && (a = a.replace(wa, "&lt;")); - 1 != a.indexOf(">") && (a = a.replace(xa, "&gt;")); - 1 != a.indexOf('"') && (a = a.replace(ya, "&quot;")); - 1 != a.indexOf("'") && (a = a.replace(za, "&#39;")); - 1 != a.indexOf("\x00") && (a = a.replace(Aa, "&#0;"));
             return a
         }
         var va = /&/g,
             wa = /</g,
             xa = />/g,
             ya = /"/g,
             za = /'/g,
             Aa = /\x00/g,
             ua = /[\x00&<>"']/;
 
         function Ba(a, b) {
             return a < b ? -1 : a > b ? 1 : 0
         }
 
         function Ca(a, b) {
             b.unshift(a);
             pa.call(this, ra.apply(null, b));
             b.shift()
         }
         r(Ca, pa);
         Ca.prototype.name = "AssertionError";
 
         function Da(a, b) {
             throw new Ca("Failure" + (a ? ": " + a : ""), Array.prototype.slice.call(arguments, 1));
         }
         var Ea = Array.prototype.indexOf ? function(a, b, c) {
                 return Array.prototype.indexOf.call(a, b, c)
             } : function(a, b, c) {
                 c = null == c ? 0 : 0 > c ? Math.max(0, a.length + c) : c;
                 if (n(a)) return n(b) && 1 == b.length ? a.indexOf(b, c) : -1;
                 for (; c < a.length; c++)
                     if (c in
                         a && a[c] === b) return c;
                 return -1
             },
             Fa = Array.prototype.forEach ? function(a, b, c) {
                 Array.prototype.forEach.call(a, b, c)
             } : function(a, b, c) {
                 for (var d = a.length, e = n(a) ? a.split("") : a, f = 0; f < d; f++) f in e && b.call(c, e[f], f, a)
             };
 
         function Ga(a, b) {
             for (var c = n(a) ? a.split("") : a, d = a.length - 1; 0 <= d; --d) d in c && b.call(void 0, c[d], d, a)
         }
         var Ha = Array.prototype.filter ? function(a, b, c) {
                 return Array.prototype.filter.call(a, b, c)
             } : function(a, b, c) {
                 for (var d = a.length, e = [], f = 0, g = n(a) ? a.split("") : a, k = 0; k < d; k++)
                     if (k in g) {
                         var C = g[k];
                         b.call(c,
                             C, k, a) && (e[f++] = C)
                     } return e
             },
             Ia = Array.prototype.map ? function(a, b, c) {
                 return Array.prototype.map.call(a, b, c)
             } : function(a, b, c) {
                 for (var d = a.length, e = Array(d), f = n(a) ? a.split("") : a, g = 0; g < d; g++) g in f && (e[g] = b.call(c, f[g], g, a));
                 return e
             },
             Ja = Array.prototype.some ? function(a, b, c) {
                 return Array.prototype.some.call(a, b, c)
             } : function(a, b, c) {
                 for (var d = a.length, e = n(a) ? a.split("") : a, f = 0; f < d; f++)
                     if (f in e && b.call(c, e[f], f, a)) return !0;
                 return !1
             };
 
         function Ka(a, b, c) {
             for (var d = a.length, e = n(a) ? a.split("") : a, f = 0; f < d; f++)
                 if (f in
                     e && b.call(c, e[f], f, a)) return f;
             return -1
         }
 
         function La(a, b) {
             return 0 <= Ea(a, b)
         }
 
         function Ma(a, b) {
             b = Ea(a, b);
             var c;
             (c = 0 <= b) && Na(a, b);
             return c
         }
 
         function Na(a, b) {
             return 1 == Array.prototype.splice.call(a, b, 1).length
         }
 
         function Oa(a, b) {
             b = Ka(a, b, void 0);
             0 <= b && Na(a, b)
         }
 
         function Pa(a, b) {
             var c = 0;
             Ga(a, function(d, e) {
                 b.call(void 0, d, e, a) && Na(a, e) && c++
             })
         }
 
         function Qa(a) {
             return Array.prototype.concat.apply([], arguments)
         }
 
         function Ra(a) {
             var b = a.length;
             if (0 < b) {
                 for (var c = Array(b), d = 0; d < b; d++) c[d] = a[d];
                 return c
             }
             return []
         }
         var Sa;
         a: {
             var Ta = l.navigator;
             if (Ta) {
                 var Ua = Ta.userAgent;
                 if (Ua) {
                     Sa = Ua;
                     break a
                 }
             }
             Sa = ""
         }
 
         function t(a) {
             return -1 != Sa.indexOf(a)
         }
 
         function Va(a, b, c) {
             for (var d in a) b.call(c, a[d], d, a)
         }
 
         function Wa(a, b) {
             for (var c in a)
                 if (b.call(void 0, a[c], c, a)) return !0;
             return !1
         }
 
         function Xa(a) {
             var b = [],
                 c = 0,
                 d;
             for (d in a) b[c++] = a[d];
             return b
         }
 
         function Ya(a) {
             var b = [],
                 c = 0,
                 d;
             for (d in a) b[c++] = d;
             return b
         }
 
         function Za(a) {
             var b = {},
                 c;
             for (c in a) b[c] = a[c];
             return b
         }
         var $a = "constructor hasOwnProperty isPrototypeOf propertyIsEnumerable toLocaleString toString valueOf".split(" ");
 
         function ab(a, b) {
             for (var c, d, e = 1; e < arguments.length; e++) {
                 d = arguments[e];
                 for (c in d) a[c] = d[c];
                 for (var f = 0; f < $a.length; f++) c = $a[f], Object.prototype.hasOwnProperty.call(d, c) && (a[c] = d[c])
             }
         }
 
         function bb(a) {
             var b = arguments.length;
             if (1 == b && da(arguments[0])) return bb.apply(null, arguments[0]);
             for (var c = {}, d = 0; d < b; d++) c[arguments[d]] = !0;
             return c
         }
 
         function cb(a) {
             cb[" "](a);
             return a
         }
         cb[" "] = aa;
 
         function db(a, b) {
             var c = eb;
             return Object.prototype.hasOwnProperty.call(c, a) ? c[a] : c[a] = b(a)
         }
         var fb = t("Opera"),
             u = t("Trident") ||
             t("MSIE"),
             gb = t("Edge"),
             hb = gb || u,
             jb = t("Gecko") && !(-1 != Sa.toLowerCase().indexOf("webkit") && !t("Edge")) && !(t("Trident") || t("MSIE")) && !t("Edge"),
             v = -1 != Sa.toLowerCase().indexOf("webkit") && !t("Edge"),
             kb = v && t("Mobile"),
             lb = t("Macintosh");
 
         function mb() {
             var a = l.document;
             return a ? a.documentMode : void 0
         }
         var nb;
         a: {
             var ob = "",
                 pb = function() {
                     var a = Sa;
                     if (jb) return /rv\:([^\);]+)(\)|;)/.exec(a);
                     if (gb) return /Edge\/([\d\.]+)/.exec(a);
                     if (u) return /\b(?:MSIE|rv)[: ]([^\);]+)(\)|;)/.exec(a);
                     if (v) return /WebKit\/(\S+)/.exec(a);
                     if (fb) return /(?:Version)[ \/]?(\S+)/.exec(a)
                 }();pb && (ob = pb ? pb[1] : "");
             if (u) {
                 var qb = mb();
                 if (null != qb && qb > parseFloat(ob)) {
                     nb = String(qb);
                     break a
                 }
             }
             nb = ob
         }
         var eb = {};
 
         function w(a) {
             return db(a, function() {
                 for (var b = 0, c = sa(String(nb)).split("."), d = sa(String(a)).split("."), e = Math.max(c.length, d.length), f = 0; 0 == b && f < e; f++) {
                     var g = c[f] || "",
                         k = d[f] || "";
                     do {
                         g = /(\d*)(\D*)(.*)/.exec(g) || ["", "", "", ""];
                         k = /(\d*)(\D*)(.*)/.exec(k) || ["", "", "", ""];
                         if (0 == g[0].length && 0 == k[0].length) break;
                         b = Ba(0 == g[1].length ? 0 : parseInt(g[1], 10),
                             0 == k[1].length ? 0 : parseInt(k[1], 10)) || Ba(0 == g[2].length, 0 == k[2].length) || Ba(g[2], k[2]);
                         g = g[3];
                         k = k[3]
                     } while (0 == b)
                 }
                 return 0 <= b
             })
         }
         var rb = l.document,
             sb = rb && u ? mb() || ("CSS1Compat" == rb.compatMode ? parseInt(nb, 10) : 5) : void 0;
         var tb = !u || 9 <= Number(sb),
             ub = !jb && !u || u && 9 <= Number(sb) || jb && w("1.9.1");
         u && w("9");
 
         function x(a) {
             this.ye = a
         }
         x.prototype.toString = function() {
             return this.ye
         };
         var vb = new x("A"),
             wb = new x("APPLET"),
             xb = new x("AREA"),
             yb = new x("BASE"),
             zb = new x("BR"),
             Ab = new x("BUTTON"),
             Bb = new x("COL"),
             Cb = new x("COMMAND"),
             Db = new x("DIV"),
             Eb = new x("EMBED"),
             Fb = new x("FRAME"),
             Gb = new x("HEAD"),
             Hb = new x("HR"),
             Ib = new x("IFRAME"),
             Jb = new x("IMG"),
             Kb = new x("INPUT"),
             Lb = new x("ISINDEX"),
             Mb = new x("KEYGEN"),
             Nb = new x("LINK"),
             Ob = new x("MATH"),
             Pb = new x("META"),
             Qb = new x("NOFRAMES"),
             Rb = new x("NOSCRIPT"),
             Sb = new x("OBJECT"),
             Tb = new x("PARAM"),
             Ub = new x("SCRIPT"),
             Vb = new x("SOURCE"),
             Wb = new x("STYLE"),
             Xb = new x("SVG"),
             Yb = new x("TEMPLATE"),
             Zb = new x("TEXTAREA"),
             $b = new x("TRACK"),
             ac = new x("WBR");
 
         function bc() {
             this.Ub = "";
             this.td = cc
         }
         bc.prototype.Db = !0;
         bc.prototype.xb = function() {
             return this.Ub
         };
         bc.prototype.toString = function() {
             return "Const{" + this.Ub + "}"
         };
         var cc = {};
 
         function dc(a) {
             var b = new bc;
             b.Ub = a;
             return b
         }
         dc("");
 
         function ec() {
             this.Nb = "";
             this.ud = fc
         }
         ec.prototype.Db = !0;
         ec.prototype.xb = function() {
             return this.Nb
         };
         ec.prototype.jc = function() {
             return 1
         };
         ec.prototype.toString = function() {
             return "TrustedResourceUrl{" + this.Nb + "}"
         };
 
         function gc() {
             var a = dc("//www.gstatic.com/accountchooser/client.js");
             a instanceof bc && a.constructor === bc && a.td === cc ? a = a.Ub : (Da("expected object of type Const, got '" +
                 a + "'"), a = "type_error:Const");
             var b = new ec;
             b.Nb = a;
             return b
         }
         var fc = {};
 
         function hc() {
             this.ia = "";
             this.sd = ic
         }
         hc.prototype.Db = !0;
         hc.prototype.xb = function() {
             return this.ia
         };
         hc.prototype.jc = function() {
             return 1
         };
         hc.prototype.toString = function() {
             return "SafeUrl{" + this.ia + "}"
         };
 
         function jc(a) {
             if (a instanceof hc && a.constructor === hc && a.sd === ic) return a.ia;
             Da("expected object of type SafeUrl, got '" + a + "' of type " + ba(a));
             return "type_error:SafeUrl"
         }
         var kc = /^(?:(?:https?|mailto|ftp):|[^&:/?#]*(?:[/?#]|$))/i;
 
         function lc(a) {
             if (a instanceof hc) return a;
             a = a.Db ? a.xb() : String(a);
             kc.test(a) || (a = "about:invalid#zClosurez");
             return mc(a)
         }
         var ic = {};
 
         function mc(a) {
             var b = new hc;
             b.ia = a;
             return b
         }
         mc("about:blank");
 
         function nc() {
             this.ia = "";
             this.rd = pc;
             this.Lc = null
         }
         nc.prototype.jc = function() {
             return this.Lc
         };
         nc.prototype.Db = !0;
         nc.prototype.xb = function() {
             return this.ia
         };
         nc.prototype.toString = function() {
             return "SafeHtml{" + this.ia + "}"
         };
 
         function qc(a) {
             if (a instanceof nc && a.constructor === nc && a.rd === pc) return a.ia;
             Da("expected object of type SafeHtml, got '" +
                 a + "' of type " + ba(a));
             return "type_error:SafeHtml"
         }
         bb(wb, yb, Eb, Ib, Nb, Ob, Pb, Sb, Ub, Wb, Xb, Yb);
         var pc = {};
         nc.prototype.de = function(a) {
             this.ia = a;
             this.Lc = null;
             return this
         };
 
         function rc(a, b) {
             this.x = m(a) ? a : 0;
             this.y = m(b) ? b : 0
         }
         h = rc.prototype;
         h.clone = function() {
             return new rc(this.x, this.y)
         };
         h.toString = function() {
             return "(" + this.x + ", " + this.y + ")"
         };
         h.ceil = function() {
             this.x = Math.ceil(this.x);
             this.y = Math.ceil(this.y);
             return this
         };
         h.floor = function() {
             this.x = Math.floor(this.x);
             this.y = Math.floor(this.y);
             return this
         };
         h.round =
             function() {
                 this.x = Math.round(this.x);
                 this.y = Math.round(this.y);
                 return this
             };
         h.translate = function(a, b) {
             a instanceof rc ? (this.x += a.x, this.y += a.y) : (this.x += Number(a), fa(b) && (this.y += b));
             return this
         };
         h.scale = function(a, b) {
             b = fa(b) ? b : a;
             this.x *= a;
             this.y *= b;
             return this
         };
 
         function sc(a, b) {
             this.width = a;
             this.height = b
         }
         h = sc.prototype;
         h.clone = function() {
             return new sc(this.width, this.height)
         };
         h.toString = function() {
             return "(" + this.width + " x " + this.height + ")"
         };
         h.yd = function() {
             return this.width * this.height
         };
         h.Fb = function() {
             return !this.yd()
         };
         h.ceil = function() {
             this.width = Math.ceil(this.width);
             this.height = Math.ceil(this.height);
             return this
         };
         h.floor = function() {
             this.width = Math.floor(this.width);
             this.height = Math.floor(this.height);
             return this
         };
         h.round = function() {
             this.width = Math.round(this.width);
             this.height = Math.round(this.height);
             return this
         };
         h.scale = function(a, b) {
             b = fa(b) ? b : a;
             this.width *= a;
             this.height *= b;
             return this
         };
 
         function tc(a) {
             return a ? new uc(vc(a)) : qa || (qa = new uc)
         }
 
         function wc(a, b) {
             var c = b || document;
             return c.querySelectorAll && c.querySelector ?
                 c.querySelectorAll("." + a) : xc(a, b)
         }
 
         function yc(a, b) {
             var c = b || document;
             return (c.getElementsByClassName ? c.getElementsByClassName(a)[0] : c.querySelectorAll && c.querySelector ? c.querySelector("." + a) : xc(a, b)[0]) || null
         }
 
         function xc(a, b) {
             var c, d, e;
             c = document;
             b = b || c;
             if (b.querySelectorAll && b.querySelector && a) return b.querySelectorAll("" + (a ? "." + a : ""));
             if (a && b.getElementsByClassName) {
                 var f = b.getElementsByClassName(a);
                 return f
             }
             f = b.getElementsByTagName("*");
             if (a) {
                 e = {};
                 for (c = d = 0; b = f[c]; c++) {
                     var g = b.className;
                     "function" ==
                     typeof g.split && La(g.split(/\s+/), a) && (e[d++] = b)
                 }
                 e.length = d;
                 return e
             }
             return f
         }
 
         function zc(a, b) {
             Va(b, function(b, d) {
                 "style" == d ? a.style.cssText = b : "class" == d ? a.className = b : "for" == d ? a.htmlFor = b : Ac.hasOwnProperty(d) ? a.setAttribute(Ac[d], b) : 0 == d.lastIndexOf("aria-", 0) || 0 == d.lastIndexOf("data-", 0) ? a.setAttribute(d, b) : a[d] = b
             })
         }
         var Ac = {
             cellpadding: "cellPadding",
             cellspacing: "cellSpacing",
             colspan: "colSpan",
             frameborder: "frameBorder",
             height: "height",
             maxlength: "maxLength",
             nonce: "nonce",
             role: "role",
             rowspan: "rowSpan",
             type: "type",
             usemap: "useMap",
             valign: "vAlign",
             width: "width"
         };
 
         function Bc(a) {
             return a.scrollingElement ? a.scrollingElement : v || "CSS1Compat" != a.compatMode ? a.body || a.documentElement : a.documentElement
         }
 
         function Cc(a, b, c, d) {
             function e(c) {
                 c && b.appendChild(n(c) ? a.createTextNode(c) : c)
             }
             for (; d < c.length; d++) {
                 var f = c[d];
                 !ea(f) || ha(f) && 0 < f.nodeType ? e(f) : Fa(Dc(f) ? Ra(f) : f, e)
             }
         }
 
         function Ec(a) {
             return a && a.parentNode ? a.parentNode.removeChild(a) : null
         }
 
         function vc(a) {
             return 9 == a.nodeType ? a : a.ownerDocument || a.document
         }
 
         function Fc(a,
             b) {
             if ("textContent" in a) a.textContent = b;
             else if (3 == a.nodeType) a.data = b;
             else if (a.firstChild && 3 == a.firstChild.nodeType) {
                 for (; a.lastChild != a.firstChild;) a.removeChild(a.lastChild);
                 a.firstChild.data = b
             } else {
                 for (var c; c = a.firstChild;) a.removeChild(c);
                 a.appendChild(vc(a).createTextNode(String(b)))
             }
         }
 
         function Dc(a) {
             if (a && "number" == typeof a.length) {
                 if (ha(a)) return "function" == typeof a.item || "string" == typeof a.item;
                 if (ga(a)) return "function" == typeof a.item
             }
             return !1
         }
 
         function Gc(a, b) {
             return b ? Hc(a, function(a) {
                 return !b ||
                     n(a.className) && La(a.className.split(/\s+/), b)
             }) : null
         }
 
         function Hc(a, b) {
             for (var c = 0; a;) {
                 if (b(a)) return a;
                 a = a.parentNode;
                 c++
             }
             return null
         }
 
         function uc(a) {
             this.Y = a || l.document || document
         }
         h = uc.prototype;
         h.Oa = tc;
         h.L = function(a) {
             return n(a) ? this.Y.getElementById(a) : a
         };
         h.getElementsByTagName = function(a, b) {
             return (b || this.Y).getElementsByTagName(String(a))
         };
         h.kc = function(a, b) {
             return wc(a, b || this.Y)
         };
         h.o = function(a, b) {
             return yc(a, b || this.Y)
         };
         h.fc = function(a, b, c) {
             var d = this.Y,
                 e = arguments,
                 f = String(e[0]),
                 g = e[1];
             if (!tb && g && (g.name || g.type)) {
                 f = ["<", f];
                 g.name && f.push(' name="', ta(g.name), '"');
                 if (g.type) {
                     f.push(' type="', ta(g.type), '"');
                     var k = {};
                     ab(k, g);
                     delete k.type;
                     g = k
                 }
                 f.push(">");
                 f = f.join("")
             }
             f = d.createElement(f);
             g && (n(g) ? f.className = g : da(g) ? f.className = g.join(" ") : zc(f, g));
             2 < e.length && Cc(d, f, e, 2);
             return f
         };
         h.createElement = function(a) {
             return this.Y.createElement(String(a))
         };
         h.createTextNode = function(a) {
             return this.Y.createTextNode(String(a))
         };
         h.appendChild = function(a, b) {
             a.appendChild(b)
         };
         h.append = function(a,
             b) {
             Cc(vc(a), a, arguments, 1)
         };
         h.canHaveChildren = function(a) {
             if (1 != a.nodeType) return !1;
             switch (a.tagName) {
                 case String(wb):
                 case String(xb):
                 case String(yb):
                 case String(zb):
                 case String(Bb):
                 case String(Cb):
                 case String(Eb):
                 case String(Fb):
                 case String(Hb):
                 case String(Jb):
                 case String(Kb):
                 case String(Ib):
                 case String(Lb):
                 case String(Mb):
                 case String(Nb):
                 case String(Qb):
                 case String(Rb):
                 case String(Pb):
                 case String(Sb):
                 case String(Tb):
                 case String(Ub):
                 case String(Vb):
                 case String(Wb):
                 case String($b):
                 case String(ac):
                     return !1
             }
             return !0
         };
         h.removeNode = Ec;
         h.Pc = function(a) {
             return ub && void 0 != a.children ? a.children : Ha(a.childNodes, function(a) {
                 return 1 == a.nodeType
             })
         };
         h.contains = function(a, b) {
             if (!a || !b) return !1;
             if (a.contains && 1 == b.nodeType) return a == b || a.contains(b);
             if ("undefined" != typeof a.compareDocumentPosition) return a == b || !!(a.compareDocumentPosition(b) & 16);
             for (; b && a != b;) b = b.parentNode;
             return b == a
         };
         u && w(8);
 
         function Ic(a) {
             if (a.R && "function" == typeof a.R) return a.R();
             if (n(a)) return a.split("");
             if (ea(a)) {
                 for (var b = [], c = a.length, d = 0; d < c; d++) b.push(a[d]);
                 return b
             }
             return Xa(a)
         }
 
         function Jc(a) {
             if (a.ga && "function" == typeof a.ga) return a.ga();
             if (!a.R || "function" != typeof a.R) {
                 if (ea(a) || n(a)) {
                     var b = [];
                     a = a.length;
                     for (var c = 0; c < a; c++) b.push(c);
                     return b
                 }
                 return Ya(a)
             }
         }
 
         function Kc(a, b, c) {
             if (a.forEach && "function" == typeof a.forEach) a.forEach(b, c);
             else if (ea(a) || n(a)) Fa(a, b, c);
             else
                 for (var d = Jc(a), e = Ic(a), f = e.length, g = 0; g < f; g++) b.call(c, e[g], d && d[g], a)
         }
         var Lc = "StopIteration" in l ? l.StopIteration : {
             message: "StopIteration",
             stack: ""
         };
 
         function Mc() {}
         Mc.prototype.next =
             function() {
                 throw Lc;
             };
         Mc.prototype.va = function() {
             return this
         };
 
         function Nc(a) {
             if (a instanceof Mc) return a;
             if ("function" == typeof a.va) return a.va(!1);
             if (ea(a)) {
                 var b = 0,
                     c = new Mc;
                 c.next = function() {
                     for (;;) {
                         if (b >= a.length) throw Lc;
                         if (b in a) return a[b++];
                         b++
                     }
                 };
                 return c
             }
             throw Error("Not implemented");
         }
 
         function Oc(a, b) {
             if (ea(a)) try {
                 Fa(a, b, void 0)
             } catch (c) {
                 if (c !== Lc) throw c;
             } else {
                 a = Nc(a);
                 try {
                     for (;;) b.call(void 0, a.next(), void 0, a)
                 } catch (c$1) {
                     if (c$1 !== Lc) throw c$1;
                 }
             }
         }
 
         function Pc(a) {
             if (ea(a)) return Ra(a);
             a =
                 Nc(a);
             var b = [];
             Oc(a, function(a) {
                 b.push(a)
             });
             return b
         }
 
         function Qc(a, b) {
             this.V = {};
             this.u = [];
             this.Va = this.v = 0;
             var c = arguments.length;
             if (1 < c) {
                 if (c % 2) throw Error("Uneven number of arguments");
                 for (var d = 0; d < c; d += 2) this.set(arguments[d], arguments[d + 1])
             } else a && this.addAll(a)
         }
         h = Qc.prototype;
         h.R = function() {
             Rc(this);
             for (var a = [], b = 0; b < this.u.length; b++) a.push(this.V[this.u[b]]);
             return a
         };
         h.ga = function() {
             Rc(this);
             return this.u.concat()
         };
         h.La = function(a) {
             return Sc(this.V, a)
         };
         h.Fb = function() {
             return 0 == this.v
         };
         h.clear = function() {
             this.V = {};
             this.Va = this.v = this.u.length = 0
         };
         h.remove = function(a) {
             return Sc(this.V, a) ? (delete this.V[a], this.v--, this.Va++, this.u.length > 2 * this.v && Rc(this), !0) : !1
         };
 
         function Rc(a) {
             if (a.v != a.u.length) {
                 for (var b = 0, c = 0; b < a.u.length;) {
                     var d = a.u[b];
                     Sc(a.V, d) && (a.u[c++] = d);
                     b++
                 }
                 a.u.length = c
             }
             if (a.v != a.u.length) {
                 for (var e = {}, c = b = 0; b < a.u.length;) d = a.u[b], Sc(e, d) || (a.u[c++] = d, e[d] = 1), b++;
                 a.u.length = c
             }
         }
         h.get = function(a, b) {
             return Sc(this.V, a) ? this.V[a] : b
         };
         h.set = function(a, b) {
             Sc(this.V, a) || (this.v++,
                 this.u.push(a), this.Va++);
             this.V[a] = b
         };
         h.addAll = function(a) {
             var b;
             a instanceof Qc ? (b = a.ga(), a = a.R()) : (b = Ya(a), a = Xa(a));
             for (var c = 0; c < b.length; c++) this.set(b[c], a[c])
         };
         h.forEach = function(a, b) {
             for (var c = this.ga(), d = 0; d < c.length; d++) {
                 var e = c[d],
                     f = this.get(e);
                 a.call(b, f, e, this)
             }
         };
         h.clone = function() {
             return new Qc(this)
         };
         h.va = function(a) {
             Rc(this);
             var b = 0,
                 c = this.Va,
                 d = this,
                 e = new Mc;
             e.next = function() {
                 if (c != d.Va) throw Error("The map has changed since the iterator was created");
                 if (b >= d.u.length) throw Lc;
                 var e =
                     d.u[b++];
                 return a ? e : d.V[e]
             };
             return e
         };
 
         function Sc(a, b) {
             return Object.prototype.hasOwnProperty.call(a, b)
         }
         var Tc = /^(?:([^:/?#.]+):)?(?:\/\/(?:([^/?#]*)@)?([^/#?]*?)(?::([0-9]+))?(?=[/#?]|$))?([^?#]+)?(?:\?([^#]*))?(?:#([\s\S]*))?$/;
 
         function Uc(a, b) {
             if (a) {
                 a = a.split("&");
                 for (var c = 0; c < a.length; c++) {
                     var d = a[c].indexOf("="),
                         e, f = null;
                     0 <= d ? (e = a[c].substring(0, d), f = a[c].substring(d + 1)) : e = a[c];
                     b(e, f ? decodeURIComponent(f.replace(/\+/g, " ")) : "")
                 }
             }
         }
 
         function Vc(a, b, c, d) {
             for (var e = c.length; 0 <= (b = a.indexOf(c, b)) &&
                 b < d;) {
                 var f = a.charCodeAt(b - 1);
                 if (38 == f || 63 == f)
                     if (f = a.charCodeAt(b + e), !f || 61 == f || 38 == f || 35 == f) return b;
                 b += e + 1
             }
             return -1
         }
         var Wc = /#|$/;
 
         function Xc(a, b) {
             var c = a.search(Wc),
                 d = Vc(a, 0, b, c);
             if (0 > d) return null;
             var e = a.indexOf("&", d);
             if (0 > e || e > c) e = c;
             d += b.length + 1;
             return decodeURIComponent(a.substr(d, e - d).replace(/\+/g, " "))
         }
         var Yc = /[?&]($|#)/;
 
         function Zc(a, b) {
             this.fa = this.Ja = this.ua = "";
             this.Sa = null;
             this.za = this.da = "";
             this.S = this.ee = !1;
             var c;
             a instanceof Zc ? (this.S = m(b) ? b : a.S, $c(this, a.ua), c = a.Ja, y(this), this.Ja =
                 c, c = a.fa, y(this), this.fa = c, ad(this, a.Sa), c = a.da, y(this), this.da = c, bd(this, a.ja.clone()), a = a.za, y(this), this.za = a) : a && (c = String(a).match(Tc)) ? (this.S = !!b, $c(this, c[1] || "", !0), a = c[2] || "", y(this), this.Ja = cd(a), a = c[3] || "", y(this), this.fa = cd(a, !0), ad(this, c[4]), a = c[5] || "", y(this), this.da = cd(a, !0), bd(this, c[6] || "", !0), a = c[7] || "", y(this), this.za = cd(a)) : (this.S = !!b, this.ja = new dd(null, 0, this.S))
         }
         Zc.prototype.toString = function() {
             var a = [],
                 b = this.ua;
             b && a.push(ed(b, fd, !0), ":");
             var c = this.fa;
             if (c || "file" == b) a.push("//"),
                 (b = this.Ja) && a.push(ed(b, fd, !0), "@"), a.push(encodeURIComponent(String(c)).replace(/%25([0-9a-fA-F]{2})/g, "%$1")), c = this.Sa, null != c && a.push(":", String(c));
             if (c = this.da) this.fa && "/" != c.charAt(0) && a.push("/"), a.push(ed(c, "/" == c.charAt(0) ? gd : hd, !0));
             (c = this.ja.toString()) && a.push("?", c);
             (c = this.za) && a.push("#", ed(c, id));
             return a.join("")
         };
         Zc.prototype.resolve = function(a) {
             var b = this.clone(),
                 c = !!a.ua;
             c ? $c(b, a.ua) : c = !!a.Ja;
             if (c) {
                 var d = a.Ja;
                 y(b);
                 b.Ja = d
             } else c = !!a.fa;
             c ? (d = a.fa, y(b), b.fa = d) : c = null != a.Sa;
             d =
                 a.da;
             if (c) ad(b, a.Sa);
             else if (c = !!a.da) {
                 if ("/" != d.charAt(0))
                     if (this.fa && !this.da) d = "/" + d;
                     else {
                         var e = b.da.lastIndexOf("/"); - 1 != e && (d = b.da.substr(0, e + 1) + d)
                     } e = d;
                 if (".." == e || "." == e) d = "";
                 else if (-1 != e.indexOf("./") || -1 != e.indexOf("/.")) {
                     for (var d = 0 == e.lastIndexOf("/", 0), e = e.split("/"), f = [], g = 0; g < e.length;) {
                         var k = e[g++];
                         "." == k ? d && g == e.length && f.push("") : ".." == k ? ((1 < f.length || 1 == f.length && "" != f[0]) && f.pop(), d && g == e.length && f.push("")) : (f.push(k), d = !0)
                     }
                     d = f.join("/")
                 } else d = e
             }
             c ? (y(b), b.da = d) : c = "" !== a.ja.toString();
             c ? bd(b, a.ja.clone()) : c = !!a.za;
             c && (a = a.za, y(b), b.za = a);
             return b
         };
         Zc.prototype.clone = function() {
             return new Zc(this)
         };
 
         function $c(a, b, c) {
             y(a);
             a.ua = c ? cd(b, !0) : b;
             a.ua && (a.ua = a.ua.replace(/:$/, ""))
         }
 
         function ad(a, b) {
             y(a);
             if (b) {
                 b = Number(b);
                 if (isNaN(b) || 0 > b) throw Error("Bad port number " + b);
                 a.Sa = b
             } else a.Sa = null
         }
 
         function bd(a, b, c) {
             y(a);
             b instanceof dd ? (a.ja = b, a.ja.Ac(a.S)) : (c || (b = ed(b, jd)), a.ja = new dd(b, 0, a.S))
         }
 
         function y(a) {
             if (a.ee) throw Error("Tried to modify a read-only Uri");
         }
         Zc.prototype.Ac = function(a) {
             this.S =
                 a;
             this.ja && this.ja.Ac(a);
             return this
         };
 
         function kd(a) {
             return a instanceof Zc ? a.clone() : new Zc(a, void 0)
         }
 
         function ld(a) {
             var b = window.location.href;
             b instanceof Zc || (b = kd(b));
             a instanceof Zc || (a = kd(a));
             return b.resolve(a)
         }
 
         function cd(a, b) {
             return a ? b ? decodeURI(a.replace(/%25/g, "%2525")) : decodeURIComponent(a) : ""
         }
 
         function ed(a, b, c) {
             return n(a) ? (a = encodeURI(a).replace(b, md), c && (a = a.replace(/%25([0-9a-fA-F]{2})/g, "%$1")), a) : null
         }
 
         function md(a) {
             a = a.charCodeAt(0);
             return "%" + (a >> 4 & 15).toString(16) + (a & 15).toString(16)
         }
         var fd = /[#\/\?@]/g,
             hd = /[\#\?:]/g,
             gd = /[\#\?]/g,
             jd = /[\#\?@]/g,
             id = /#/g;
 
         function dd(a, b, c) {
             this.v = this.C = null;
             this.O = a || null;
             this.S = !!c
         }
 
         function nd(a) {
             a.C || (a.C = new Qc, a.v = 0, a.O && Uc(a.O, function(b, c) {
                 a.add(decodeURIComponent(b.replace(/\+/g, " ")), c)
             }))
         }
         h = dd.prototype;
         h.add = function(a, b) {
             nd(this);
             this.O = null;
             a = od(this, a);
             var c = this.C.get(a);
             c || this.C.set(a, c = []);
             c.push(b);
             this.v += 1;
             return this
         };
         h.remove = function(a) {
             nd(this);
             a = od(this, a);
             return this.C.La(a) ? (this.O = null, this.v -= this.C.get(a).length, this.C.remove(a)) :
                 !1
         };
         h.clear = function() {
             this.C = this.O = null;
             this.v = 0
         };
         h.Fb = function() {
             nd(this);
             return 0 == this.v
         };
         h.La = function(a) {
             nd(this);
             a = od(this, a);
             return this.C.La(a)
         };
         h.ga = function() {
             nd(this);
             for (var a = this.C.R(), b = this.C.ga(), c = [], d = 0; d < b.length; d++)
                 for (var e = a[d], f = 0; f < e.length; f++) c.push(b[d]);
             return c
         };
         h.R = function(a) {
             nd(this);
             var b = [];
             if (n(a)) this.La(a) && (b = Qa(b, this.C.get(od(this, a))));
             else {
                 a = this.C.R();
                 for (var c = 0; c < a.length; c++) b = Qa(b, a[c])
             }
             return b
         };
         h.set = function(a, b) {
             nd(this);
             this.O = null;
             a = od(this,
                 a);
             this.La(a) && (this.v -= this.C.get(a).length);
             this.C.set(a, [b]);
             this.v += 1;
             return this
         };
         h.get = function(a, b) {
             a = a ? this.R(a) : [];
             return 0 < a.length ? String(a[0]) : b
         };
         h.toString = function() {
             if (this.O) return this.O;
             if (!this.C) return "";
             for (var a = [], b = this.C.ga(), c = 0; c < b.length; c++)
                 for (var d = b[c], e = encodeURIComponent(String(d)), d = this.R(d), f = 0; f < d.length; f++) {
                     var g = e;
                     "" !== d[f] && (g += "=" + encodeURIComponent(String(d[f])));
                     a.push(g)
                 }
             return this.O = a.join("&")
         };
         h.clone = function() {
             var a = new dd;
             a.O = this.O;
             this.C && (a.C =
                 this.C.clone(), a.v = this.v);
             return a
         };
 
         function od(a, b) {
             b = String(b);
             a.S && (b = b.toLowerCase());
             return b
         }
         h.Ac = function(a) {
             a && !this.S && (nd(this), this.O = null, this.C.forEach(function(a, c) {
                 var d = c.toLowerCase();
                 c != d && (this.remove(c), this.remove(d), 0 < a.length && (this.O = null, this.C.set(od(this, d), Ra(a)), this.v += a.length))
             }, this));
             this.S = a
         };
         h.extend = function(a) {
             for (var b = 0; b < arguments.length; b++) Kc(arguments[b], function(a, b) {
                 this.add(b, a)
             }, this)
         };
         var pd = {
                 af: !0
             },
             qd = {
                 cf: !0
             },
             rd = {
                 bf: !0
             };
 
         function z() {
             throw Error("Do not instantiate directly");
         }
         z.prototype.oa = null;
         z.prototype.toString = function() {
             return this.content
         };
 
         function sd(a, b, c, d) {
             a: if (a = a(b || td, void 0, c), d = (d || tc()).createElement(Db), a = ud(a), a.match(vd), d.innerHTML = a, 1 == d.childNodes.length && (a = d.firstChild, 1 == a.nodeType)) {
                 d = a;
                 break a
             }return d
         }
 
         function ud(a) {
             if (!ha(a)) return String(a);
             if (a instanceof z) {
                 if (a.X === pd) return a.content;
                 if (a.X === rd) return ta(a.content)
             }
             Da("Soy template output is unsafe for use as HTML: " + a);
             return "zSoyz"
         }
         var vd = /^<(body|caption|col|colgroup|head|html|tr|td|th|tbody|thead|tfoot)>/i,
             td = {};
 
         function wd(a) {
             if (null != a) switch (a.oa) {
                 case 1:
                     return 1;
                 case -1:
                     return -1;
                 case 0:
                     return 0
             }
             return null
         }
 
         function xd() {
             z.call(this)
         }
         r(xd, z);
         xd.prototype.X = pd;
 
         function A(a) {
             return null != a && a.X === pd ? a : a instanceof nc ? B(qc(a), a.jc()) : B(ta(String(String(a))), wd(a))
         }
 
         function yd() {
             z.call(this)
         }
         r(yd, z);
         yd.prototype.X = {
             $e: !0
         };
         yd.prototype.oa = 1;
 
         function zd() {
             z.call(this)
         }
         r(zd, z);
         zd.prototype.X = qd;
         zd.prototype.oa = 1;
 
         function Ad() {
             z.call(this)
         }
         r(Ad, z);
         Ad.prototype.X = {
             Ze: !0
         };
         Ad.prototype.oa = 1;
 
         function Bd() {
             z.call(this)
         }
         r(Bd, z);
         Bd.prototype.X = {
             Ye: !0
         };
         Bd.prototype.oa = 1;
 
         function Cd(a, b) {
             this.content = String(a);
             this.oa = null != b ? b : null
         }
         r(Cd, z);
         Cd.prototype.X = rd;
 
         function Dd(a) {
             function b(a) {
                 this.content = a
             }
             b.prototype = a.prototype;
             return function(a) {
                 return new b(String(a))
             }
         }
 
         function D(a) {
             return new Cd(a, void 0)
         }
         var B = function(a) {
             function b(a) {
                 this.content = a
             }
             b.prototype = a.prototype;
             return function(a, d) {
                 a = new b(String(a));
                 void 0 !== d && (a.oa = d);
                 return a
             }
         }(xd);
         Dd(yd);
         var Ed = Dd(zd);
         Dd(Ad);
         Dd(Bd);
 
         function Fd(a) {
             var b = {
                 label: Gd("New password")
             };
 
             function c() {}
             c.prototype = a;
             a = new c;
             for (var d in b) a[d] = b[d];
             return a
         }
 
         function Gd(a) {
             return (a = String(a)) ? new Cd(a, void 0) : ""
         }
         var Hd = function(a) {
             function b(a) {
                 this.content = a
             }
             b.prototype = a.prototype;
             return function(a, d) {
                 a = String(a);
                 if (!a) return "";
                 a = new b(a);
                 void 0 !== d && (a.oa = d);
                 return a
             }
         }(xd);
 
         function Id(a) {
             return null != a && a.X === pd ? String(String(a.content).replace(Jd, "").replace(Kd, "&lt;")).replace(Ld, Md) : ta(String(a))
         }
 
         function Nd(a) {
             null != a && a.X === qd ? a = String(a).replace(Od, Pd) : a instanceof hc ? a = String(jc(a)).replace(Od,
                 Pd) : (a = String(a), Qd.test(a) ? a = a.replace(Od, Pd) : (Da("Bad value `%s` for |filterNormalizeUri", [a]), a = "#zSoyz"));
             return a
         }
         var Rd = {
             "\x00": "&#0;",
             "\t": "&#9;",
             "\n": "&#10;",
             "\x0B": "&#11;",
             "\f": "&#12;",
             "\r": "&#13;",
             " ": "&#32;",
             '"': "&quot;",
             "&": "&amp;",
             "'": "&#39;",
             "-": "&#45;",
             "/": "&#47;",
             "<": "&lt;",
             "=": "&#61;",
             ">": "&gt;",
             "`": "&#96;",
             "\u0085": "&#133;",
             "\u00a0": "&#160;",
             "\u2028": "&#8232;",
             "\u2029": "&#8233;"
         };
 
         function Md(a) {
             return Rd[a]
         }
         var Sd = {
             "\x00": "%00",
             "\u0001": "%01",
             "\u0002": "%02",
             "\u0003": "%03",
             "\u0004": "%04",
             "\u0005": "%05",
             "\u0006": "%06",
             "\u0007": "%07",
             "\b": "%08",
             "\t": "%09",
             "\n": "%0A",
             "\x0B": "%0B",
             "\f": "%0C",
             "\r": "%0D",
             "\u000e": "%0E",
             "\u000f": "%0F",
             "\u0010": "%10",
             "\u0011": "%11",
             "\u0012": "%12",
             "\u0013": "%13",
             "\u0014": "%14",
             "\u0015": "%15",
             "\u0016": "%16",
             "\u0017": "%17",
             "\u0018": "%18",
             "\u0019": "%19",
             "\u001a": "%1A",
             "\u001b": "%1B",
             "\u001c": "%1C",
             "\u001d": "%1D",
             "\u001e": "%1E",
             "\u001f": "%1F",
             " ": "%20",
             '"': "%22",
             "'": "%27",
             "(": "%28",
             ")": "%29",
             "<": "%3C",
             ">": "%3E",
             "\\": "%5C",
             "{": "%7B",
             "}": "%7D",
             "\u007f": "%7F",
             "\u0085": "%C2%85",
             "\u00a0": "%C2%A0",
             "\u2028": "%E2%80%A8",
             "\u2029": "%E2%80%A9",
             "\uff01": "%EF%BC%81",
             "\uff03": "%EF%BC%83",
             "\uff04": "%EF%BC%84",
             "\uff06": "%EF%BC%86",
             "\uff07": "%EF%BC%87",
             "\uff08": "%EF%BC%88",
             "\uff09": "%EF%BC%89",
             "\uff0a": "%EF%BC%8A",
             "\uff0b": "%EF%BC%8B",
             "\uff0c": "%EF%BC%8C",
             "\uff0f": "%EF%BC%8F",
             "\uff1a": "%EF%BC%9A",
             "\uff1b": "%EF%BC%9B",
             "\uff1d": "%EF%BC%9D",
             "\uff1f": "%EF%BC%9F",
             "\uff20": "%EF%BC%A0",
             "\uff3b": "%EF%BC%BB",
             "\uff3d": "%EF%BC%BD"
         };
 
         function Pd(a) {
             return Sd[a]
         }
         var Ld = /[\x00\x22\x27\x3c\x3e]/g,
             Od =
             /[\x00- \x22\x27-\x29\x3c\x3e\\\x7b\x7d\x7f\x85\xa0\u2028\u2029\uff01\uff03\uff04\uff06-\uff0c\uff0f\uff1a\uff1b\uff1d\uff1f\uff20\uff3b\uff3d]/g,
             Qd = /^(?![^#?]*\/(?:\.|%2E){2}(?:[\/?#]|$))(?:(?:https?|mailto):|[^&:\/?#]*(?:[\/?#]|$))/i,
             Jd = /<(?:!|\/?([a-zA-Z][a-zA-Z0-9:\-]*))(?:[^>'"]|"[^"]*"|'[^']*')*>/g,
             Kd = /</g;
 
         function Td(a) {
             a.prototype.then = a.prototype.then;
             a.prototype.$goog_Thenable = !0
         }
 
         function Ud(a) {
             if (!a) return !1;
             try {
                 return !!a.$goog_Thenable
             } catch (b) {
                 return !1
             }
         }
 
         function Vd(a, b, c) {
             this.fe = c;
             this.Fd =
                 a;
             this.re = b;
             this.Kb = 0;
             this.Bb = null
         }
         Vd.prototype.get = function() {
             var a;
             0 < this.Kb ? (this.Kb--, a = this.Bb, this.Bb = a.next, a.next = null) : a = this.Fd();
             return a
         };
         Vd.prototype.put = function(a) {
             this.re(a);
             this.Kb < this.fe && (this.Kb++, a.next = this.Bb, this.Bb = a)
         };
 
         function Wd() {
             this.Xb = this.Wa = null
         }
         var Yd = new Vd(function() {
             return new Xd
         }, function(a) {
             a.reset()
         }, 100);
         Wd.prototype.add = function(a, b) {
             var c = Yd.get();
             c.set(a, b);
             this.Xb ? this.Xb.next = c : this.Wa = c;
             this.Xb = c
         };
         Wd.prototype.remove = function() {
             var a = null;
             this.Wa && (a =
                 this.Wa, this.Wa = this.Wa.next, this.Wa || (this.Xb = null), a.next = null);
             return a
         };
 
         function Xd() {
             this.next = this.scope = this.ic = null
         }
         Xd.prototype.set = function(a, b) {
             this.ic = a;
             this.scope = b;
             this.next = null
         };
         Xd.prototype.reset = function() {
             this.next = this.scope = this.ic = null
         };
 
         function Zd(a) {
             l.setTimeout(function() {
                 throw a;
             }, 0)
         }
         var $d;
 
         function ae() {
             var a = l.MessageChannel;
             "undefined" === typeof a && "undefined" !== typeof window && window.postMessage && window.addEventListener && !t("Presto") && (a = function() {
                 var a = document.createElement(String(Ib));
                 a.style.display = "none";
                 a.src = "";
                 document.documentElement.appendChild(a);
                 var b = a.contentWindow,
                     a = b.document;
                 a.open();
                 a.write("");
                 a.close();
                 var c = "callImmediate" + Math.random(),
                     d = "file:" == b.location.protocol ? "*" : b.location.protocol + "//" + b.location.host,
                     a = p(function(a) {
                         if (("*" == d || a.origin == d) && a.data == c) this.port1.onmessage()
                     }, this);
                 b.addEventListener("message", a, !1);
                 this.port1 = {};
                 this.port2 = {
                     postMessage: function() {
                         b.postMessage(c, d)
                     }
                 }
             });
             if ("undefined" !== typeof a && !t("Trident") && !t("MSIE")) {
                 var b = new a,
                     c = {},
                     d = c;
                 b.port1.onmessage = function() {
                     if (m(c.next)) {
                         c = c.next;
                         var a = c.Hc;
                         c.Hc = null;
                         a()
                     }
                 };
                 return function(a) {
                     d.next = {
                         Hc: a
                     };
                     d = d.next;
                     b.port2.postMessage(0)
                 }
             }
             return "undefined" !== typeof document && "onreadystatechange" in document.createElement(String(Ub)) ? function(a) {
                 var b = document.createElement(String(Ub));
                 b.onreadystatechange = function() {
                     b.onreadystatechange = null;
                     b.parentNode.removeChild(b);
                     b = null;
                     a();
                     a = null
                 };
                 document.documentElement.appendChild(b)
             } : function(a) {
                 l.setTimeout(a, 0)
             }
         }
 
         function be(a, b) {
             ce || de();
             ee || (ce(), ee = !0);
             fe.add(a, b)
         }
         var ce;
 
         function de() {
             if (-1 != String(l.Promise).indexOf("[native code]")) {
                 var a = l.Promise.resolve(void 0);
                 ce = function() {
                     a.then(ge)
                 }
             } else ce = function() {
                 var a = ge;
                 !ga(l.setImmediate) || l.Window && l.Window.prototype && !t("Edge") && l.Window.prototype.setImmediate == l.setImmediate ? ($d || ($d = ae()), $d(a)) : l.setImmediate(a)
             }
         }
         var ee = !1,
             fe = new Wd;
 
         function ge() {
             for (var a; a = fe.remove();) {
                 try {
                     a.ic.call(a.scope)
                 } catch (b) {
                     Zd(b)
                 }
                 Yd.put(a)
             }
             ee = !1
         }
 
         function he(a, b) {
             this.W = ie;
             this.ka = void 0;
             this.Ka =
                 this.la = this.s = null;
             this.yb = this.hc = !1;
             if (a != aa) try {
                 var c = this;
                 a.call(b, function(a) {
                     je(c, ke, a)
                 }, function(a) {
                     if (!(a instanceof le)) try {
                         if (a instanceof Error) throw a;
                         throw Error("Promise rejected.");
                     } catch (b$2) {}
                     je(c, me, a)
                 })
             } catch (d) {
                 je(this, me, d)
             }
         }
         var ie = 0,
             ke = 2,
             me = 3;
 
         function ne() {
             this.next = this.context = this.Qa = this.jb = this.wa = null;
             this.qb = !1
         }
         ne.prototype.reset = function() {
             this.context = this.Qa = this.jb = this.wa = null;
             this.qb = !1
         };
         var oe = new Vd(function() {
             return new ne
         }, function(a) {
             a.reset()
         }, 100);
 
         function pe(a,
             b, c) {
             var d = oe.get();
             d.jb = a;
             d.Qa = b;
             d.context = c;
             return d
         }
 
         function qe(a) {
             if (a instanceof he) return a;
             var b = new he(aa);
             je(b, ke, a);
             return b
         }
 
         function re(a) {
             return new he(function(b, c) {
                 c(a)
             })
         }
         he.prototype.then = function(a, b, c) {
             return se(this, ga(a) ? a : null, ga(b) ? b : null, c)
         };
         Td(he);
 
         function te(a, b) {
             return se(a, null, b, void 0)
         }
         he.prototype.cancel = function(a) {
             this.W == ie && be(function() {
                 var b = new le(a);
                 ue(this, b)
             }, this)
         };
 
         function ue(a, b) {
             if (a.W == ie)
                 if (a.s) {
                     var c = a.s;
                     if (c.la) {
                         for (var d = 0, e = null, f = null, g = c.la; g && (g.qb ||
                                 (d++, g.wa == a && (e = g), !(e && 1 < d))); g = g.next) e || (f = g);
                         e && (c.W == ie && 1 == d ? ue(c, b) : (f ? (d = f, d.next == c.Ka && (c.Ka = d), d.next = d.next.next) : ve(c), we(c, e, me, b)))
                     }
                     a.s = null
                 } else je(a, me, b)
         }
 
         function xe(a, b) {
             a.la || a.W != ke && a.W != me || ye(a);
             a.Ka ? a.Ka.next = b : a.la = b;
             a.Ka = b
         }
 
         function se(a, b, c, d) {
             var e = pe(null, null, null);
             e.wa = new he(function(a, g) {
                 e.jb = b ? function(c) {
                     try {
                         var e = b.call(d, c);
                         a(e)
                     } catch (P) {
                         g(P)
                     }
                 } : a;
                 e.Qa = c ? function(b) {
                     try {
                         var e = c.call(d, b);
                         !m(e) && b instanceof le ? g(b) : a(e)
                     } catch (P) {
                         g(P)
                     }
                 } : g
             });
             e.wa.s = a;
             xe(a, e);
             return e.wa
         }
         he.prototype.Ce = function(a) {
             this.W = ie;
             je(this, ke, a)
         };
         he.prototype.De = function(a) {
             this.W = ie;
             je(this, me, a)
         };
 
         function je(a, b, c) {
             if (a.W == ie) {
                 a === c && (b = me, c = new TypeError("Promise cannot resolve to itself"));
                 a.W = 1;
                 var d;
                 a: {
                     var e = c,
                         f = a.Ce,
                         g = a.De;
                     if (e instanceof he) xe(e, pe(f || aa, g || null, a)),
                     d = !0;
                     else if (Ud(e)) e.then(f, g, a),
                     d = !0;
                     else {
                         if (ha(e)) try {
                             var k = e.then;
                             if (ga(k)) {
                                 ze(e, k, f, g, a);
                                 d = !0;
                                 break a
                             }
                         } catch (C) {
                             g.call(a, C);
                             d = !0;
                             break a
                         }
                         d = !1
                     }
                 }
                 d || (a.ka = c, a.W = b, a.s = null, ye(a), b != me || c instanceof le || Ae(a, c))
             }
         }
 
         function ze(a,
             b, c, d, e) {
             function f(a) {
                 k || (k = !0, d.call(e, a))
             }
 
             function g(a) {
                 k || (k = !0, c.call(e, a))
             }
             var k = !1;
             try {
                 b.call(a, g, f)
             } catch (C) {
                 f(C)
             }
         }
 
         function ye(a) {
             a.hc || (a.hc = !0, be(a.Md, a))
         }
 
         function ve(a) {
             var b = null;
             a.la && (b = a.la, a.la = b.next, b.next = null);
             a.la || (a.Ka = null);
             return b
         }
         he.prototype.Md = function() {
             for (var a; a = ve(this);) we(this, a, this.W, this.ka);
             this.hc = !1
         };
 
         function we(a, b, c, d) {
             if (c == me && b.Qa && !b.qb)
                 for (; a && a.yb; a = a.s) a.yb = !1;
             if (b.wa) b.wa.s = null, Be(b, c, d);
             else try {
                 b.qb ? b.jb.call(b.context) : Be(b, c, d)
             } catch (e) {
                 Ce.call(null,
                     e)
             }
             oe.put(b)
         }
 
         function Be(a, b, c) {
             b == ke ? a.jb.call(a.context, c) : a.Qa && a.Qa.call(a.context, c)
         }
 
         function Ae(a, b) {
             a.yb = !0;
             be(function() {
                 a.yb && Ce.call(null, b)
             })
         }
         var Ce = Zd;
 
         function le(a) {
             pa.call(this, a)
         }
         r(le, pa);
         le.prototype.name = "cancel";
 
         function De() {
             0 != Ee && (Fe[this[ia] || (this[ia] = ++ja)] = this);
             this.Ma = this.Ma;
             this.Fa = this.Fa
         }
         var Ee = 0,
             Fe = {};
         De.prototype.Ma = !1;
         De.prototype.i = function() {
             if (!this.Ma && (this.Ma = !0, this.f(), 0 != Ee)) {
                 var a = this[ia] || (this[ia] = ++ja);
                 delete Fe[a]
             }
         };
 
         function Ge(a, b) {
             a.Ma ? m(void 0) ?
                 b.call(void 0) : b() : (a.Fa || (a.Fa = []), a.Fa.push(m(void 0) ? p(b, void 0) : b))
         }
         De.prototype.f = function() {
             if (this.Fa)
                 for (; this.Fa.length;) this.Fa.shift()()
         };
 
         function He(a) {
             a && "function" == typeof a.i && a.i()
         }
         var Ie = !u || 9 <= Number(sb),
             Je = u && !w("9");
         !v || w("528");
         jb && w("1.9b") || u && w("8") || fb && w("9.5") || v && w("528");
         jb && !w("8") || u && w("9");
 
         function Ke(a, b) {
             this.type = a;
             this.currentTarget = this.target = b;
             this.defaultPrevented = this.Ga = !1;
             this.fd = !0
         }
         Ke.prototype.stopPropagation = function() {
             this.Ga = !0
         };
         Ke.prototype.preventDefault =
             function() {
                 this.defaultPrevented = !0;
                 this.fd = !1
             };
 
         function E(a, b) {
             Ke.call(this, a ? a.type : "");
             this.relatedTarget = this.currentTarget = this.target = null;
             this.button = this.screenY = this.screenX = this.clientY = this.clientX = this.offsetY = this.offsetX = 0;
             this.key = "";
             this.charCode = this.keyCode = 0;
             this.metaKey = this.shiftKey = this.altKey = this.ctrlKey = !1;
             this.$ = this.state = null;
             a && this.init(a, b)
         }
         r(E, Ke);
         E.prototype.init = function(a, b) {
             var c = this.type = a.type,
                 d = a.changedTouches ? a.changedTouches[0] : null;
             this.target = a.target ||
                 a.srcElement;
             this.currentTarget = b;
             if (b = a.relatedTarget) {
                 if (jb) {
                     var e;
                     a: {
                         try {
                             cb(b.nodeName);
                             e = !0;
                             break a
                         } catch (f) {}
                         e = !1
                     }
                     e || (b = null)
                 }
             } else "mouseover" == c ? b = a.fromElement : "mouseout" == c && (b = a.toElement);
             this.relatedTarget = b;
             null === d ? (this.offsetX = v || void 0 !== a.offsetX ? a.offsetX : a.layerX, this.offsetY = v || void 0 !== a.offsetY ? a.offsetY : a.layerY, this.clientX = void 0 !== a.clientX ? a.clientX : a.pageX, this.clientY = void 0 !== a.clientY ? a.clientY : a.pageY, this.screenX = a.screenX || 0, this.screenY = a.screenY || 0) : (this.clientX =
                 void 0 !== d.clientX ? d.clientX : d.pageX, this.clientY = void 0 !== d.clientY ? d.clientY : d.pageY, this.screenX = d.screenX || 0, this.screenY = d.screenY || 0);
             this.button = a.button;
             this.keyCode = a.keyCode || 0;
             this.key = a.key || "";
             this.charCode = a.charCode || ("keypress" == c ? a.keyCode : 0);
             this.ctrlKey = a.ctrlKey;
             this.altKey = a.altKey;
             this.shiftKey = a.shiftKey;
             this.metaKey = a.metaKey;
             this.state = a.state;
             this.$ = a;
             a.defaultPrevented && this.preventDefault()
         };
         E.prototype.stopPropagation = function() {
             E.h.stopPropagation.call(this);
             this.$.stopPropagation ?
                 this.$.stopPropagation() : this.$.cancelBubble = !0
         };
         E.prototype.preventDefault = function() {
             E.h.preventDefault.call(this);
             var a = this.$;
             if (a.preventDefault) a.preventDefault();
             else if (a.returnValue = !1, Je) try {
                 if (a.ctrlKey || 112 <= a.keyCode && 123 >= a.keyCode) a.keyCode = -1
             } catch (b) {}
         };
         var Le = "closure_listenable_" + (1E6 * Math.random() | 0);
 
         function Me(a) {
             return !(!a || !a[Le])
         }
         var Ne = 0;
 
         function Oe(a, b, c, d, e) {
             this.listener = a;
             this.Ob = null;
             this.src = b;
             this.type = c;
             this.Xa = !!d;
             this.Ab = e;
             this.key = ++Ne;
             this.Ta = this.rb = !1
         }
 
         function Pe(a) {
             a.Ta = !0;
             a.listener = null;
             a.Ob = null;
             a.src = null;
             a.Ab = null
         }
 
         function Qe(a) {
             this.src = a;
             this.K = {};
             this.pb = 0
         }
         h = Qe.prototype;
         h.add = function(a, b, c, d, e) {
             var f = a.toString();
             a = this.K[f];
             a || (a = this.K[f] = [], this.pb++);
             var g = Re(a, b, d, e); - 1 < g ? (b = a[g], c || (b.rb = !1)) : (b = new Oe(b, this.src, f, !!d, e), b.rb = c, a.push(b));
             return b
         };
         h.remove = function(a, b, c, d) {
             a = a.toString();
             if (!(a in this.K)) return !1;
             var e = this.K[a];
             b = Re(e, b, c, d);
             return -1 < b ? (Pe(e[b]), Na(e, b), 0 == e.length && (delete this.K[a], this.pb--), !0) : !1
         };
 
         function Se(a, b) {
             var c =
                 b.type;
             c in a.K && Ma(a.K[c], b) && (Pe(b), 0 == a.K[c].length && (delete a.K[c], a.pb--))
         }
         h.Pb = function(a) {
             a = a && a.toString();
             var b = 0,
                 c;
             for (c in this.K)
                 if (!a || c == a) {
                     for (var d = this.K[c], e = 0; e < d.length; e++) ++b, Pe(d[e]);
                     delete this.K[c];
                     this.pb--
                 } return b
         };
         h.cb = function(a, b, c, d) {
             a = this.K[a.toString()];
             var e = -1;
             a && (e = Re(a, b, c, d));
             return -1 < e ? a[e] : null
         };
         h.hasListener = function(a, b) {
             var c = m(a),
                 d = c ? a.toString() : "",
                 e = m(b);
             return Wa(this.K, function(a) {
                 for (var g = 0; g < a.length; ++g)
                     if (!(c && a[g].type != d || e && a[g].Xa != b)) return !0;
                 return !1
             })
         };
 
         function Re(a, b, c, d) {
             for (var e = 0; e < a.length; ++e) {
                 var f = a[e];
                 if (!f.Ta && f.listener == b && f.Xa == !!c && f.Ab == d) return e
             }
             return -1
         }
         var Te = "closure_lm_" + (1E6 * Math.random() | 0),
             Ue = {},
             Ve = 0;
 
         function We(a, b, c, d, e) {
             if (da(b)) {
                 for (var f = 0; f < b.length; f++) We(a, b[f], c, d, e);
                 return null
             }
             c = Xe(c);
             return Me(a) ? a.ra(b, c, d, e) : Ye(a, b, c, !1, d, e)
         }
 
         function Ye(a, b, c, d, e, f) {
             if (!b) throw Error("Invalid event type");
             var g = !!e,
                 k = Ze(a);
             k || (a[Te] = k = new Qe(a));
             c = k.add(b, c, d, e, f);
             if (c.Ob) return c;
             d = $e();
             c.Ob = d;
             d.src = a;
             d.listener =
                 c;
             if (a.addEventListener) a.addEventListener(b.toString(), d, g);
             else if (a.attachEvent) a.attachEvent(af(b.toString()), d);
             else throw Error("addEventListener and attachEvent are unavailable.");
             Ve++;
             return c
         }
 
         function $e() {
             var a = bf,
                 b = Ie ? function(c) {
                     return a.call(b.src, b.listener, c)
                 } : function(c) {
                     c = a.call(b.src, b.listener, c);
                     if (!c) return c
                 };
             return b
         }
 
         function cf(a, b, c, d, e) {
             if (da(b)) {
                 for (var f = 0; f < b.length; f++) cf(a, b[f], c, d, e);
                 return null
             }
             c = Xe(c);
             return Me(a) ? a.Uc(b, c, d, e) : Ye(a, b, c, !0, d, e)
         }
 
         function df(a, b, c,
             d, e) {
             if (da(b))
                 for (var f = 0; f < b.length; f++) df(a, b[f], c, d, e);
             else c = Xe(c), Me(a) ? a.Ec(b, c, d, e) : a && (a = Ze(a)) && (b = a.cb(b, c, !!d, e)) && ef(b)
         }
 
         function ef(a) {
             if (fa(a) || !a || a.Ta) return;
             var b = a.src;
             if (Me(b)) {
                 Se(b.Z, a);
                 return
             }
             var c = a.type,
                 d = a.Ob;
             b.removeEventListener ? b.removeEventListener(c, d, a.Xa) : b.detachEvent && b.detachEvent(af(c), d);
             Ve--;
             (c = Ze(b)) ? (Se(c, a), 0 == c.pb && (c.src = null, b[Te] = null)) : Pe(a)
         }
 
         function af(a) {
             return a in Ue ? Ue[a] : Ue[a] = "on" + a
         }
 
         function ff(a, b, c, d) {
             var e = !0;
             if (a = Ze(a))
                 if (b = a.K[b.toString()])
                     for (b =
                         b.concat(), a = 0; a < b.length; a++) {
                         var f = b[a];
                         f && f.Xa == c && !f.Ta && (f = gf(f, d), e = e && !1 !== f)
                     }
             return e
         }
 
         function gf(a, b) {
             var c = a.listener,
                 d = a.Ab || a.src;
             a.rb && ef(a);
             return c.call(d, b)
         }
 
         function bf(a, b) {
             if (a.Ta) return !0;
             if (!Ie) {
                 if (!b) a: {
                     b = ["window", "event"];
                     for (var c = l, d; d = b.shift();)
                         if (null != c[d]) c = c[d];
                         else {
                             b = null;
                             break a
                         } b = c
                 }
                 d = b;
                 b = new E(d, this);
                 c = !0;
                 if (!(0 > d.keyCode || void 0 != d.returnValue)) {
                     a: {
                         var e = !1;
                         if (0 == d.keyCode) try {
                             d.keyCode = -1;
                             break a
                         } catch (g) {
                             e = !0
                         }
                         if (e || void 0 == d.returnValue) d.returnValue = !0
                     }
                     d = [];
                     for (e = b.currentTarget; e; e = e.parentNode) d.push(e);a = a.type;
                     for (e = d.length - 1; !b.Ga && 0 <= e; e--) {
                         b.currentTarget = d[e];
                         var f = ff(d[e], a, !0, b),
                             c = c && f
                     }
                     for (e = 0; !b.Ga && e < d.length; e++) b.currentTarget = d[e],
                     f = ff(d[e], a, !1, b),
                     c = c && f
                 }
                 return c
             }
             return gf(a, new E(b, this))
         }
 
         function Ze(a) {
             a = a[Te];
             return a instanceof Qe ? a : null
         }
         var hf = "__closure_events_fn_" + (1E9 * Math.random() >>> 0);
 
         function Xe(a) {
             if (ga(a)) return a;
             a[hf] || (a[hf] = function(b) {
                 return a.handleEvent(b)
             });
             return a[hf]
         }
 
         function F() {
             De.call(this);
             this.Z = new Qe(this);
             this.vd = this;
             this.Mb = null
         }
         r(F, De);
         F.prototype[Le] = !0;
         h = F.prototype;
         h.Bc = function(a) {
             this.Mb = a
         };
         h.addEventListener = function(a, b, c, d) {
             We(this, a, b, c, d)
         };
         h.removeEventListener = function(a, b, c, d) {
             df(this, a, b, c, d)
         };
         h.dispatchEvent = function(a) {
             var b, c = this.Mb;
             if (c)
                 for (b = []; c; c = c.Mb) b.push(c);
             var c = this.vd,
                 d = a.type || a;
             if (n(a)) a = new Ke(a, c);
             else if (a instanceof Ke) a.target = a.target || c;
             else {
                 var e = a;
                 a = new Ke(d, c);
                 ab(a, e)
             }
             var e = !0,
                 f;
             if (b)
                 for (var g = b.length - 1; !a.Ga && 0 <= g; g--) f = a.currentTarget = b[g], e = jf(f, d, !0,
                     a) && e;
             a.Ga || (f = a.currentTarget = c, e = jf(f, d, !0, a) && e, a.Ga || (e = jf(f, d, !1, a) && e));
             if (b)
                 for (g = 0; !a.Ga && g < b.length; g++) f = a.currentTarget = b[g], e = jf(f, d, !1, a) && e;
             return e
         };
         h.f = function() {
             F.h.f.call(this);
             this.Z && this.Z.Pb(void 0);
             this.Mb = null
         };
         h.ra = function(a, b, c, d) {
             return this.Z.add(String(a), b, !1, c, d)
         };
         h.Uc = function(a, b, c, d) {
             return this.Z.add(String(a), b, !0, c, d)
         };
         h.Ec = function(a, b, c, d) {
             return this.Z.remove(String(a), b, c, d)
         };
 
         function jf(a, b, c, d) {
             b = a.Z.K[String(b)];
             if (!b) return !0;
             b = b.concat();
             for (var e = !0,
                     f = 0; f < b.length; ++f) {
                 var g = b[f];
                 if (g && !g.Ta && g.Xa == c) {
                     var k = g.listener,
                         C = g.Ab || g.src;
                     g.rb && Se(a.Z, g);
                     e = !1 !== k.call(C, d) && e
                 }
             }
             return e && 0 != d.fd
         }
         h.cb = function(a, b, c, d) {
             return this.Z.cb(String(a), b, c, d)
         };
         h.hasListener = function(a, b) {
             return this.Z.hasListener(m(a) ? String(a) : void 0, b)
         };
 
         function kf(a, b) {
             F.call(this);
             this.hb = a || 1;
             this.Ua = b || l;
             this.ac = p(this.Ae, this);
             this.vc = na()
         }
         r(kf, F);
         h = kf.prototype;
         h.enabled = !1;
         h.H = null;
         h.setInterval = function(a) {
             this.hb = a;
             this.H && this.enabled ? (this.stop(), this.start()) :
                 this.H && this.stop()
         };
         h.Ae = function() {
             if (this.enabled) {
                 var a = na() - this.vc;
                 0 < a && a < .8 * this.hb ? this.H = this.Ua.setTimeout(this.ac, this.hb - a) : (this.H && (this.Ua.clearTimeout(this.H), this.H = null), this.dispatchEvent("tick"), this.enabled && (this.H = this.Ua.setTimeout(this.ac, this.hb), this.vc = na()))
             }
         };
         h.start = function() {
             this.enabled = !0;
             this.H || (this.H = this.Ua.setTimeout(this.ac, this.hb), this.vc = na())
         };
         h.stop = function() {
             this.enabled = !1;
             this.H && (this.Ua.clearTimeout(this.H), this.H = null)
         };
         h.f = function() {
             kf.h.f.call(this);
             this.stop();
             delete this.Ua
         };
 
         function lf(a, b) {
             if (ga(a)) b && (a = p(a, b));
             else if (a && "function" == typeof a.handleEvent) a = p(a.handleEvent, a);
             else throw Error("Invalid listener argument");
             return 2147483647 < Number(0) ? -1 : l.setTimeout(a, 0)
         }
 
         function mf(a) {
             a = String(a);
             if (/^\s*$/.test(a) ? 0 : /^[\],:{}\s\u2028\u2029]*$/.test(a.replace(/\\["\\\/bfnrtu]/g, "@").replace(/(?:"[^"\\\n\r\u2028\u2029\x00-\x08\x0a-\x1f]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)[\s\u2028\u2029]*(?=:|,|]|}|$)/g, "]").replace(/(?:^|:|,)(?:[\s\u2028\u2029]*\[)+/g,
                     ""))) try {
                 return eval("(" + a + ")")
             } catch (b) {}
             throw Error("Invalid JSON string: " + a);
         }
 
         function nf(a) {
             var b = []; of (new pf, a, b);
             return b.join("")
         }
 
         function pf() {
             this.Qb = void 0
         }
 
         function of (a, b, c) {
             if (null == b) c.push("null");
             else {
                 if ("object" == typeof b) {
                     if (da(b)) {
                         var d = b;
                         b = d.length;
                         c.push("[");
                         for (var e = "", f = 0; f < b; f++) c.push(e), e = d[f], of (a, a.Qb ? a.Qb.call(d, String(f), e) : e, c), e = ",";
                         c.push("]");
                         return
                     }
                     if (b instanceof String || b instanceof Number || b instanceof Boolean) b = b.valueOf();
                     else {
                         c.push("{");
                         f = "";
                         for (d in b) Object.prototype.hasOwnProperty.call(b,
                             d) && (e = b[d], "function" != typeof e && (c.push(f), qf(d, c), c.push(":"), of (a, a.Qb ? a.Qb.call(b, d, e) : e, c), f = ","));
                         c.push("}");
                         return
                     }
                 }
                 switch (typeof b) {
                     case "string":
                         qf(b, c);
                         break;
                     case "number":
                         c.push(isFinite(b) && !isNaN(b) ? String(b) : "null");
                         break;
                     case "boolean":
                         c.push(String(b));
                         break;
                     case "function":
                         c.push("null");
                         break;
                     default:
                         throw Error("Unknown type: " + typeof b);
                 }
             }
         }
         var rf = {
                 '"': '\\"',
                 "\\": "\\\\",
                 "/": "\\/",
                 "\b": "\\b",
                 "\f": "\\f",
                 "\n": "\\n",
                 "\r": "\\r",
                 "\t": "\\t",
                 "\x0B": "\\u000b"
             },
             sf = /\uffff/.test("\uffff") ?
             /[\\\"\x00-\x1f\x7f-\uffff]/g : /[\\\"\x00-\x1f\x7f-\xff]/g;
 
         function qf(a, b) {
             b.push('"', a.replace(sf, function(a) {
                 var b = rf[a];
                 b || (b = "\\u" + (a.charCodeAt(0) | 65536).toString(16).substr(1), rf[a] = b);
                 return b
             }), '"')
         }
 
         function tf(a, b, c, d, e) {
             this.reset(a, b, c, d, e)
         }
         tf.prototype.gc = null;
         var uf = 0;
         tf.prototype.reset = function(a, b, c, d, e) {
             "number" == typeof e || uf++;
             this.od = d || na();
             this.Ea = a;
             this.Xc = b;
             this.Wc = c;
             delete this.gc
         };
         tf.prototype.hd = function(a) {
             this.Ea = a
         };
 
         function vf(a) {
             this.Yc = a;
             this.gb = this.na = this.Ea = this.s =
                 null
         }
 
         function wf(a, b) {
             this.name = a;
             this.value = b
         }
         wf.prototype.toString = function() {
             return this.name
         };
         var xf = new wf("SHOUT", 1200),
             yf = new wf("SEVERE", 1E3),
             zf = new wf("WARNING", 900),
             Af = new wf("INFO", 800),
             Bf = new wf("CONFIG", 700);
         h = vf.prototype;
         h.getName = function() {
             return this.Yc
         };
         h.getParent = function() {
             return this.s
         };
         h.Pc = function() {
             this.na || (this.na = {});
             return this.na
         };
         h.hd = function(a) {
             this.Ea = a
         };
 
         function Cf(a) {
             if (a.Ea) return a.Ea;
             if (a.s) return Cf(a.s);
             Da("Root logger has no level set.");
             return null
         }
         h.log =
             function(a, b, c) {
                 if (a.value >= Cf(this).value)
                     for (ga(b) && (b = b()), a = new tf(a, String(b), this.Yc), c && (a.gc = c), c = "log:" + a.Xc, l.console && (l.console.timeStamp ? l.console.timeStamp(c) : l.console.markTimeline && l.console.markTimeline(c)), l.msWriteProfilerMark && l.msWriteProfilerMark(c), c = this; c;) {
                         b = c;
                         var d = a;
                         if (b.gb)
                             for (var e = 0, f; f = b.gb[e]; e++) f(d);
                         c = c.getParent()
                     }
             };
 
         function Df(a) {
             Ef.log(yf, a, void 0)
         }
         h.info = function(a, b) {
             this.log(Af, a, b)
         };
         var Ff = {},
             Gf = null;
 
         function Hf() {
             Gf || (Gf = new vf(""), Ff[""] = Gf, Gf.hd(Bf))
         }
 
         function If(a) {
             Hf();
             var b;
             if (!(b = Ff[a])) {
                 b = new vf(a);
                 var c = a.lastIndexOf("."),
                     d = a.substr(c + 1),
                     c = If(a.substr(0, c));
                 c.Pc()[d] = b;
                 b.s = c;
                 Ff[a] = b
             }
             return b
         }
 
         function Jf() {
             this.ed = na()
         }
         var Kf = new Jf;
         Jf.prototype.set = function(a) {
             this.ed = a
         };
         Jf.prototype.reset = function() {
             this.set(na())
         };
         Jf.prototype.get = function() {
             return this.ed
         };
 
         function Lf(a) {
             this.ta = a || "";
             this.xe = Kf
         }
         h = Lf.prototype;
         h.Gc = !0;
         h.jd = !0;
         h.te = !0;
         h.se = !0;
         h.kd = !1;
         h.ve = !1;
 
         function Mf(a) {
             return 10 > a ? "0" + a : String(a)
         }
 
         function Nf(a, b) {
             a = (a.od - b) / 1E3;
             b = a.toFixed(3);
             var c = 0;
             if (1 > a) c = 2;
             else
                 for (; 100 > a;) c++, a *= 10;
             for (; 0 < c--;) b = " " + b;
             return b
         }
 
         function Of(a) {
             Lf.call(this, a)
         }
         r(Of, Lf);
 
         function Pf() {
             this.oe = p(this.wd, this);
             this.vb = new Of;
             this.vb.jd = !1;
             this.vb.kd = !1;
             this.Tc = this.vb.Gc = !1;
             this.Vc = "";
             this.Od = {}
         }
         Pf.prototype.wd = function(a) {
             if (!this.Od[a.Wc]) {
                 var b;
                 b = this.vb;
                 var c = [];
                 c.push(b.ta, " ");
                 if (b.jd) {
                     var d = new Date(a.od);
                     c.push("[", Mf(d.getFullYear() - 2E3) + Mf(d.getMonth() + 1) + Mf(d.getDate()) + " " + Mf(d.getHours()) + ":" + Mf(d.getMinutes()) + ":" + Mf(d.getSeconds()) + "." + Mf(Math.floor(d.getMilliseconds() /
                         10)), "] ")
                 }
                 b.te && c.push("[", Nf(a, b.xe.get()), "s] ");
                 b.se && c.push("[", a.Wc, "] ");
                 b.ve && c.push("[", a.Ea.name, "] ");
                 c.push(a.Xc);
                 b.kd && (d = a.gc) && c.push("\n", d instanceof Error ? d.message : d.toString());
                 b.Gc && c.push("\n");
                 b = c.join("");
                 if (c = Qf) switch (a.Ea) {
                     case xf:
                         Rf(c, "info", b);
                         break;
                     case yf:
                         Rf(c, "error", b);
                         break;
                     case zf:
                         Rf(c, "warn", b);
                         break;
                     default:
                         Rf(c, "debug", b)
                 } else this.Vc += b
             }
         };
         var Qf = l.console;
 
         function Rf(a, b, c) {
             if (a[b]) a[b](c);
             else a.log(c)
         }
 
         function Sf(a) {
             if (a.altKey && !a.ctrlKey || a.metaKey || 112 <=
                 a.keyCode && 123 >= a.keyCode) return !1;
             switch (a.keyCode) {
                 case 18:
                 case 20:
                 case 93:
                 case 17:
                 case 40:
                 case 35:
                 case 27:
                 case 36:
                 case 45:
                 case 37:
                 case 224:
                 case 91:
                 case 144:
                 case 12:
                 case 34:
                 case 33:
                 case 19:
                 case 255:
                 case 44:
                 case 39:
                 case 145:
                 case 16:
                 case 38:
                 case 252:
                 case 224:
                 case 92:
                     return !1;
                 case 0:
                     return !jb;
                 default:
                     return 166 > a.keyCode || 183 < a.keyCode
             }
         }
 
         function Tf(a, b, c, d, e, f) {
             if (!(u || gb || v && w("525"))) return !0;
             if (lb && e) return Uf(a);
             if (e && !d) return !1;
             fa(b) && (b = Vf(b));
             e = 17 == b || 18 == b || lb && 91 == b;
             if ((!c || lb) && e || lb && 16 ==
                 b && (d || f)) return !1;
             if ((v || gb) && d && c) switch (a) {
                 case 220:
                 case 219:
                 case 221:
                 case 192:
                 case 186:
                 case 189:
                 case 187:
                 case 188:
                 case 190:
                 case 191:
                 case 192:
                 case 222:
                     return !1
             }
             if (u && d && b == a) return !1;
             switch (a) {
                 case 13:
                     return !0;
                 case 27:
                     return !(v || gb)
             }
             return Uf(a)
         }
 
         function Uf(a) {
             if (48 <= a && 57 >= a || 96 <= a && 106 >= a || 65 <= a && 90 >= a || (v || gb) && 0 == a) return !0;
             switch (a) {
                 case 32:
                 case 43:
                 case 63:
                 case 64:
                 case 107:
                 case 109:
                 case 110:
                 case 111:
                 case 186:
                 case 59:
                 case 189:
                 case 187:
                 case 61:
                 case 188:
                 case 190:
                 case 191:
                 case 192:
                 case 222:
                 case 219:
                 case 220:
                 case 221:
                     return !0;
                 default:
                     return !1
             }
         }
 
         function Vf(a) {
             if (jb) a = Wf(a);
             else if (lb && v) a: switch (a) {
                 case 93:
                     a = 91;
                     break a
             }
             return a
         }
 
         function Wf(a) {
             switch (a) {
                 case 61:
                     return 187;
                 case 59:
                     return 186;
                 case 173:
                     return 189;
                 case 224:
                     return 91;
                 case 0:
                     return 224;
                 default:
                     return a
             }
         }
 
         function Xf(a, b, c, d) {
             this.top = a;
             this.right = b;
             this.bottom = c;
             this.left = d
         }
         h = Xf.prototype;
         h.clone = function() {
             return new Xf(this.top, this.right, this.bottom, this.left)
         };
         h.toString = function() {
             return "(" + this.top + "t, " + this.right + "r, " + this.bottom + "b, " + this.left + "l)"
         };
         h.contains = function(a) {
             return this && a ? a instanceof Xf ? a.left >= this.left && a.right <= this.right && a.top >= this.top && a.bottom <= this.bottom : a.x >= this.left && a.x <= this.right && a.y >= this.top && a.y <= this.bottom : !1
         };
         h.expand = function(a, b, c, d) {
             ha(a) ? (this.top -= a.top, this.right += a.right, this.bottom += a.bottom, this.left -= a.left) : (this.top -= a, this.right += Number(b), this.bottom += Number(c), this.left -= Number(d));
             return this
         };
         h.ceil = function() {
             this.top = Math.ceil(this.top);
             this.right = Math.ceil(this.right);
             this.bottom = Math.ceil(this.bottom);
             this.left = Math.ceil(this.left);
             return this
         };
         h.floor = function() {
             this.top = Math.floor(this.top);
             this.right = Math.floor(this.right);
             this.bottom = Math.floor(this.bottom);
             this.left = Math.floor(this.left);
             return this
         };
         h.round = function() {
             this.top = Math.round(this.top);
             this.right = Math.round(this.right);
             this.bottom = Math.round(this.bottom);
             this.left = Math.round(this.left);
             return this
         };
         h.translate = function(a, b) {
             a instanceof rc ? (this.left += a.x, this.right += a.x, this.top += a.y, this.bottom += a.y) : (this.left += a, this.right +=
                 a, fa(b) && (this.top += b, this.bottom += b));
             return this
         };
         h.scale = function(a, b) {
             b = fa(b) ? b : a;
             this.left *= a;
             this.right *= a;
             this.top *= b;
             this.bottom *= b;
             return this
         };
 
         function Yf(a, b) {
             var c = vc(a);
             return c.defaultView && c.defaultView.getComputedStyle && (a = c.defaultView.getComputedStyle(a, null)) ? a[b] || a.getPropertyValue(b) || "" : ""
         }
 
         function Zf(a) {
             var b;
             try {
                 b = a.getBoundingClientRect()
             } catch (c) {
                 return {
                     left: 0,
                     top: 0,
                     right: 0,
                     bottom: 0
                 }
             }
             u && a.ownerDocument.body && (a = a.ownerDocument, b.left -= a.documentElement.clientLeft + a.body.clientLeft,
                 b.top -= a.documentElement.clientTop + a.body.clientTop);
             return b
         }
 
         function $f(a, b) {
             b = b || Bc(document);
             var c;
             c = b || Bc(document);
             var d = ag(a),
                 e = ag(c),
                 f;
             if (!u || 9 <= Number(sb)) g = Yf(c, "borderLeftWidth"), f = Yf(c, "borderRightWidth"), k = Yf(c, "borderTopWidth"), C = Yf(c, "borderBottomWidth"), f = new Xf(parseFloat(k), parseFloat(f), parseFloat(C), parseFloat(g));
             else {
                 var g = bg(c, "borderLeft");
                 f = bg(c, "borderRight");
                 var k = bg(c, "borderTop"),
                     C = bg(c, "borderBottom");
                 f = new Xf(k, f, C, g)
             }
             c == Bc(document) ? (g = d.x - c.scrollLeft, d = d.y - c.scrollTop,
                 !u || 10 <= Number(sb) || (g += f.left, d += f.top)) : (g = d.x - e.x - f.left, d = d.y - e.y - f.top);
             e = a.offsetWidth;
             f = a.offsetHeight;
             k = v && !e && !f;
             m(e) && !k || !a.getBoundingClientRect ? a = new sc(e, f) : (a = Zf(a), a = new sc(a.right - a.left, a.bottom - a.top));
             e = c.clientHeight - a.height;
             f = c.scrollLeft;
             k = c.scrollTop;
             f += Math.min(g, Math.max(g - (c.clientWidth - a.width), 0));
             k += Math.min(d, Math.max(d - e, 0));
             c = new rc(f, k);
             b.scrollLeft = c.x;
             b.scrollTop = c.y
         }
 
         function ag(a) {
             var b = vc(a),
                 c = new rc(0, 0),
                 d;
             d = b ? vc(b) : document;
             d = !u || 9 <= Number(sb) || "CSS1Compat" ==
                 tc(d).Y.compatMode ? d.documentElement : d.body;
             if (a == d) return c;
             a = Zf(a);
             d = tc(b).Y;
             b = Bc(d);
             d = d.parentWindow || d.defaultView;
             b = u && w("10") && d.pageYOffset != b.scrollTop ? new rc(b.scrollLeft, b.scrollTop) : new rc(d.pageXOffset || b.scrollLeft, d.pageYOffset || b.scrollTop);
             c.x = a.left + b.x;
             c.y = a.top + b.y;
             return c
         }
         var cg = {
             thin: 2,
             medium: 4,
             thick: 6
         };
 
         function bg(a, b) {
             if ("none" == (a.currentStyle ? a.currentStyle[b + "Style"] : null)) return 0;
             var c = a.currentStyle ? a.currentStyle[b + "Width"] : null;
             if (c in cg) a = cg[c];
             else if (/^\d+px?$/.test(c)) a =
                 parseInt(c, 10);
             else {
                 b = a.style.left;
                 var d = a.runtimeStyle.left;
                 a.runtimeStyle.left = a.currentStyle.left;
                 a.style.left = c;
                 c = a.style.pixelLeft;
                 a.style.left = b;
                 a.runtimeStyle.left = d;
                 a = +c
             }
             return a
         }
 
         function dg(a, b) {
             this.Rb = [];
             this.Zc = a;
             this.Jc = b || null;
             this.fb = this.Na = !1;
             this.ka = void 0;
             this.Cc = this.Ad = this.$b = !1;
             this.Vb = 0;
             this.s = null;
             this.bc = 0
         }
         dg.prototype.cancel = function(a) {
             if (this.Na) this.ka instanceof dg && this.ka.cancel();
             else {
                 if (this.s) {
                     var b = this.s;
                     delete this.s;
                     a ? b.cancel(a) : (b.bc--, 0 >= b.bc && b.cancel())
                 }
                 this.Zc ?
                     this.Zc.call(this.Jc, this) : this.Cc = !0;
                 this.Na || (a = new eg, fg(this), gg(this, !1, a))
             }
         };
         dg.prototype.Ic = function(a, b) {
             this.$b = !1;
             gg(this, a, b)
         };
 
         function gg(a, b, c) {
             a.Na = !0;
             a.ka = c;
             a.fb = !b;
             hg(a)
         }
 
         function fg(a) {
             if (a.Na) {
                 if (!a.Cc) throw new ig;
                 a.Cc = !1
             }
         }
 
         function jg(a, b, c) {
             a.Rb.push([b, c, void 0]);
             a.Na && hg(a)
         }
         dg.prototype.then = function(a, b, c) {
             var d, e, f = new he(function(a, b) {
                 d = a;
                 e = b
             });
             jg(this, d, function(a) {
                 a instanceof eg ? f.cancel() : e(a)
             });
             return f.then(a, b, c)
         };
         Td(dg);
 
         function kg(a) {
             return Ja(a.Rb, function(a) {
                 return ga(a[1])
             })
         }
 
         function hg(a) {
             if (a.Vb && a.Na && kg(a)) {
                 var b = a.Vb,
                     c = lg[b];
                 c && (l.clearTimeout(c.Ba), delete lg[b]);
                 a.Vb = 0
             }
             a.s && (a.s.bc--, delete a.s);
             for (var b = a.ka, d = c = !1; a.Rb.length && !a.$b;) {
                 var e = a.Rb.shift(),
                     f = e[0],
                     g = e[1],
                     e = e[2];
                 if (f = a.fb ? g : f) try {
                     var k = f.call(e || a.Jc, b);
                     m(k) && (a.fb = a.fb && (k == b || k instanceof Error), a.ka = b = k);
                     if (Ud(b) || "function" === typeof l.Promise && b instanceof l.Promise) d = !0, a.$b = !0
                 } catch (C) {
                     b = C, a.fb = !0, kg(a) || (c = !0)
                 }
             }
             a.ka = b;
             d && (k = p(a.Ic, a, !0), d = p(a.Ic, a, !1), b instanceof dg ? (jg(b, k, d), b.Ad = !0) : b.then(k,
                 d));
             c && (b = new mg(b), lg[b.Ba] = b, a.Vb = b.Ba)
         }
 
         function ig() {
             pa.call(this)
         }
         r(ig, pa);
         ig.prototype.message = "Deferred has already fired";
         ig.prototype.name = "AlreadyCalledError";
 
         function eg() {
             pa.call(this)
         }
         r(eg, pa);
         eg.prototype.message = "Deferred was canceled";
         eg.prototype.name = "CanceledError";
 
         function mg(a) {
             this.Ba = l.setTimeout(p(this.ze, this), 0);
             this.Ld = a
         }
         mg.prototype.ze = function() {
             delete lg[this.Ba];
             throw this.Ld;
         };
         var lg = {};
 
         function ng(a) {
             De.call(this);
             this.qc = a;
             this.u = {}
         }
         r(ng, De);
         var og = [];
         h = ng.prototype;
         h.ra = function(a, b, c, d) {
             da(b) || (b && (og[0] = b.toString()), b = og);
             for (var e = 0; e < b.length; e++) {
                 var f = We(a, b[e], c || this.handleEvent, d || !1, this.qc || this);
                 if (!f) break;
                 this.u[f.key] = f
             }
             return this
         };
         h.Uc = function(a, b, c, d) {
             return pg(this, a, b, c, d)
         };
 
         function pg(a, b, c, d, e, f) {
             if (da(c))
                 for (var g = 0; g < c.length; g++) pg(a, b, c[g], d, e, f);
             else {
                 b = cf(b, c, d || a.handleEvent, e, f || a.qc || a);
                 if (!b) return a;
                 a.u[b.key] = b
             }
             return a
         }
         h.Ec = function(a, b, c, d, e) {
             if (da(b))
                 for (var f = 0; f < b.length; f++) this.Ec(a, b[f], c, d, e);
             else c = c || this.handleEvent,
                 e = e || this.qc || this, c = Xe(c), d = !!d, b = Me(a) ? a.cb(b, c, d, e) : a ? (a = Ze(a)) ? a.cb(b, c, d, e) : null : null, b && (ef(b), delete this.u[b.key]);
             return this
         };
         h.Pb = function() {
             Va(this.u, function(a, b) {
                 this.u.hasOwnProperty(b) && ef(a)
             }, this);
             this.u = {}
         };
         h.f = function() {
             ng.h.f.call(this);
             this.Pb()
         };
         h.handleEvent = function() {
             throw Error("EventHandler.handleEvent not implemented");
         };
 
         function qg() {}
         qg.ca = void 0;
         qg.Pd = function() {
             return qg.ca ? qg.ca : qg.ca = new qg
         };
         qg.prototype.ie = 0;
 
         function rg(a) {
             F.call(this);
             this.$a = a || tc();
             this.Ba =
                 null;
             this.Ca = !1;
             this.j = null;
             this.pa = void 0;
             this.sb = this.na = this.s = null;
             this.Fe = !1
         }
         r(rg, F);
         h = rg.prototype;
         h.Zd = qg.Pd();
         h.L = function() {
             return this.j
         };
         h.kc = function(a) {
             return this.j ? this.$a.kc(a, this.j) : []
         };
         h.o = function(a) {
             return this.j ? this.$a.o(a, this.j) : null
         };
 
         function sg(a) {
             a.pa || (a.pa = new ng(a));
             return a.pa
         }
         h.getParent = function() {
             return this.s
         };
         h.Bc = function(a) {
             if (this.s && this.s != a) throw Error("Method not supported");
             rg.h.Bc.call(this, a)
         };
         h.Oa = function() {
             return this.$a
         };
         h.fc = function() {
             this.j = this.$a.createElement(Db)
         };
         h.render = function(a) {
             if (this.Ca) throw Error("Component already rendered");
             this.j || this.fc();
             a ? a.insertBefore(this.j, null) : this.$a.Y.body.appendChild(this.j);
             this.s && !this.s.Ca || this.m()
         };
         h.m = function() {
             this.Ca = !0;
             tg(this, function(a) {
                 !a.Ca && a.L() && a.m()
             })
         };
         h.bb = function() {
             tg(this, function(a) {
                 a.Ca && a.bb()
             });
             this.pa && this.pa.Pb();
             this.Ca = !1
         };
         h.f = function() {
             this.Ca && this.bb();
             this.pa && (this.pa.i(), delete this.pa);
             tg(this, function(a) {
                 a.i()
             });
             !this.Fe && this.j && Ec(this.j);
             this.s = this.j = this.sb = this.na = null;
             rg.h.f.call(this)
         };
 
         function tg(a, b) {
             a.na && Fa(a.na, b, void 0)
         }
         h.removeChild = function(a, b) {
             if (a) {
                 var c = n(a) ? a : a.Ba || (a.Ba = ":" + (a.Zd.ie++).toString(36));
                 this.sb && c ? (a = this.sb, a = (null !== a && c in a ? a[c] : void 0) || null) : a = null;
                 if (c && a) {
                     var d = this.sb;
                     c in d && delete d[c];
                     Ma(this.na, a);
                     b && (a.bb(), a.j && Ec(a.j));
                     b = a;
                     if (null == b) throw Error("Unable to set parent component");
                     b.s = null;
                     rg.h.Bc.call(b, null)
                 }
             }
             if (!a) throw Error("Child is not in parent component");
             return a
         };
 
         function ug(a) {
             if (a.classList) return a.classList;
             a = a.className;
             return n(a) && a.match(/\S+/g) || []
         }
 
         function vg(a, b) {
             return a.classList ? a.classList.contains(b) : La(ug(a), b)
         }
 
         function wg(a, b) {
             a.classList ? a.classList.add(b) : vg(a, b) || (a.className += 0 < a.className.length ? " " + b : b)
         }
 
         function xg(a, b) {
             a.classList ? a.classList.remove(b) : vg(a, b) && (a.className = Ha(ug(a), function(a) {
                 return a != b
             }).join(" "))
         }
 
         function yg(a, b) {
             F.call(this);
             a && (this.Hb && this.detach(), this.j = a, this.Gb = We(this.j, "keypress", this, b), this.uc = We(this.j, "keydown", this.zb, b, this), this.Hb = We(this.j,
                 "keyup", this.Xd, b, this))
         }
         r(yg, F);
         h = yg.prototype;
         h.j = null;
         h.Gb = null;
         h.uc = null;
         h.Hb = null;
         h.P = -1;
         h.qa = -1;
         h.Zb = !1;
         var zg = {
                 3: 13,
                 12: 144,
                 63232: 38,
                 63233: 40,
                 63234: 37,
                 63235: 39,
                 63236: 112,
                 63237: 113,
                 63238: 114,
                 63239: 115,
                 63240: 116,
                 63241: 117,
                 63242: 118,
                 63243: 119,
                 63244: 120,
                 63245: 121,
                 63246: 122,
                 63247: 123,
                 63248: 44,
                 63272: 46,
                 63273: 36,
                 63275: 35,
                 63276: 33,
                 63277: 34,
                 63289: 144,
                 63302: 45
             },
             Ag = {
                 Up: 38,
                 Down: 40,
                 Left: 37,
                 Right: 39,
                 Enter: 13,
                 F1: 112,
                 F2: 113,
                 F3: 114,
                 F4: 115,
                 F5: 116,
                 F6: 117,
                 F7: 118,
                 F8: 119,
                 F9: 120,
                 F10: 121,
                 F11: 122,
                 F12: 123,
                 "U+007F": 46,
                 Home: 36,
                 End: 35,
                 PageUp: 33,
                 PageDown: 34,
                 Insert: 45
             },
             Bg = u || gb || v && w("525"),
             Cg = lb && jb;
         h = yg.prototype;
         h.zb = function(a) {
             if (v || gb)
                 if (17 == this.P && !a.ctrlKey || 18 == this.P && !a.altKey || lb && 91 == this.P && !a.metaKey) this.qa = this.P = -1; - 1 == this.P && (a.ctrlKey && 17 != a.keyCode ? this.P = 17 : a.altKey && 18 != a.keyCode ? this.P = 18 : a.metaKey && 91 != a.keyCode && (this.P = 91));
             Bg && !Tf(a.keyCode, this.P, a.shiftKey, a.ctrlKey, a.altKey, a.metaKey) ? this.handleEvent(a) : (this.qa = Vf(a.keyCode), Cg && (this.Zb = a.altKey))
         };
         h.Xd = function(a) {
             this.qa = this.P = -1;
             this.Zb = a.altKey
         };
         h.handleEvent = function(a) {
             var b = a.$,
                 c, d, e = b.altKey;
             u && "keypress" == a.type ? (c = this.qa, d = 13 != c && 27 != c ? b.keyCode : 0) : (v || gb) && "keypress" == a.type ? (c = this.qa, d = 0 <= b.charCode && 63232 > b.charCode && Uf(c) ? b.charCode : 0) : fb && !v ? (c = this.qa, d = Uf(c) ? b.keyCode : 0) : (c = b.keyCode || this.qa, d = b.charCode || 0, Cg && (e = this.Zb), lb && 63 == d && 224 == c && (c = 191));
             var f = c = Vf(c);
             c ? 63232 <= c && c in zg ? f = zg[c] : 25 == c && a.shiftKey && (f = 9) : b.keyIdentifier && b.keyIdentifier in Ag && (f = Ag[b.keyIdentifier]);
             a = f == this.P;
             this.P = f;
             b =
                 new Dg(f, d, a, b);
             b.altKey = e;
             this.dispatchEvent(b)
         };
         h.L = function() {
             return this.j
         };
         h.detach = function() {
             this.Gb && (ef(this.Gb), ef(this.uc), ef(this.Hb), this.Hb = this.uc = this.Gb = null);
             this.j = null;
             this.qa = this.P = -1
         };
         h.f = function() {
             yg.h.f.call(this);
             this.detach()
         };
 
         function Dg(a, b, c, d) {
             E.call(this, d);
             this.type = "key";
             this.keyCode = a;
             this.charCode = b;
             this.repeat = c
         }
         r(Dg, E);
         var Eg = !u && !(t("Safari") && !((t("Chrome") || t("CriOS")) && !t("Edge") || t("Coast") || t("Opera") || t("Edge") || t("Silk") || t("Android")));
 
         function Fg(a,
             b) {
             return Eg && a.dataset ? b in a.dataset ? a.dataset[b] : null : a.getAttribute("data-" + String(b).replace(/([A-Z])/g, "-$1").toLowerCase())
         }
 
         function G(a) {
             var b = a.type;
             if (!m(b)) return null;
             switch (b.toLowerCase()) {
                 case "checkbox":
                 case "radio":
                     return a.checked ? a.value : null;
                 case "select-one":
                     return b = a.selectedIndex, 0 <= b ? a.options[b].value : null;
                 case "select-multiple":
                     for (var b = [], c, d = 0; c = a.options[d]; d++) c.selected && b.push(c.value);
                     return b.length ? b : null;
                 default:
                     return m(a.value) ? a.value : null
             }
         }
 
         function Gg(a, b) {
             var c;
             try {
                 c = "number" == typeof a.selectionStart
             } catch (d) {
                 c = !1
             }
             c ? (a.selectionStart = b, a.selectionEnd = b) : u && !w("9") && ("textarea" == a.type && (b = a.value.substring(0, b).replace(/(\r\n|\r|\n)/g, "\n").length), a = a.createTextRange(), a.collapse(!0), a.move("character", b), a.select())
         }
         var Hg = /^[+a-zA-Z0-9_.!#$%&'*\/=?^`{|}~-]+@([a-zA-Z0-9-]+\.)+[a-zA-Z0-9]{2,63}$/;
 
         function Ig(a) {
             F.call(this);
             this.j = a;
             We(a, Jg, this.zb, !1, this);
             We(a, "click", this.Sc, !1, this)
         }
         r(Ig, F);
         var Jg = jb ? "keypress" : "keydown";
         Ig.prototype.zb = function(a) {
             (13 ==
                 a.keyCode || v && 3 == a.keyCode) && Kg(this, a)
         };
         Ig.prototype.Sc = function(a) {
             Kg(this, a)
         };
 
         function Kg(a, b) {
             var c = new Lg(b);
             if (a.dispatchEvent(c)) {
                 c = new Mg(b);
                 try {
                     a.dispatchEvent(c)
                 } finally {
                     b.stopPropagation()
                 }
             }
         }
         Ig.prototype.f = function() {
             Ig.h.f.call(this);
             df(this.j, Jg, this.zb, !1, this);
             df(this.j, "click", this.Sc, !1, this);
             delete this.j
         };
 
         function Mg(a) {
             E.call(this, a.$);
             this.type = "action"
         }
         r(Mg, E);
 
         function Lg(a) {
             E.call(this, a.$);
             this.type = "beforeaction"
         }
         r(Lg, E);
 
         function Ng(a) {
             F.call(this);
             this.j = a;
             a = u ? "focusout" :
                 "blur";
             this.ge = We(this.j, u ? "focusin" : "focus", this, !u);
             this.he = We(this.j, a, this, !u)
         }
         r(Ng, F);
         Ng.prototype.handleEvent = function(a) {
             var b = new E(a.$);
             b.type = "focusin" == a.type || "focus" == a.type ? "focusin" : "focusout";
             this.dispatchEvent(b)
         };
         Ng.prototype.f = function() {
             Ng.h.f.call(this);
             ef(this.ge);
             ef(this.he);
             delete this.j
         };
 
         function Og(a) {
             F.call(this);
             this.H = null;
             this.j = a;
             a = u || gb || v && !w("531") && a.tagName == Zb;
             this.Oc = new ng(this);
             this.Oc.ra(this.j, a ? ["keydown", "paste", "cut", "drop", "input"] : "input", this)
         }
         r(Og,
             F);
         Og.prototype.handleEvent = function(a) {
             if ("input" == a.type) u && w(10) && 0 == a.keyCode && 0 == a.charCode || (Pg(this), this.dispatchEvent(Qg(a)));
             else if ("keydown" != a.type || Sf(a)) {
                 var b = "keydown" == a.type ? this.j.value : null;
                 u && 229 == a.keyCode && (b = null);
                 var c = Qg(a);
                 Pg(this);
                 this.H = lf(function() {
                     this.H = null;
                     this.j.value != b && this.dispatchEvent(c)
                 }, this)
             }
         };
 
         function Pg(a) {
             null != a.H && (l.clearTimeout(a.H), a.H = null)
         }
 
         function Qg(a) {
             a = new E(a.$);
             a.type = "input";
             return a
         }
         Og.prototype.f = function() {
             Og.h.f.call(this);
             this.Oc.i();
             Pg(this);
             delete this.j
         };
 
         function Rg(a) {
             var b = {},
                 c = b.document || document,
                 d;
             a instanceof ec && a.constructor === ec && a.ud === fc ? d = a.Nb : (Da("expected object of type TrustedResourceUrl, got '" + a + "' of type " + ba(a)), d = "type_error:TrustedResourceUrl");
             var e = document.createElement(String(Ub));
             a = {
                 gd: e,
                 pd: void 0
             };
             var f = new dg(Sg, a),
                 g = null,
                 k = null != b.timeout ? b.timeout : 5E3;
             0 < k && (g = window.setTimeout(function() {
                 Tg(e, !0);
                 var a = new Ug(Vg, "Timeout reached for loading script " + d);
                 fg(f);
                 gg(f, !1, a)
             }, k), a.pd = g);
             e.onload = e.onreadystatechange =
                 function() {
                     e.readyState && "loaded" != e.readyState && "complete" != e.readyState || (Tg(e, b.Re || !1, g), fg(f), gg(f, !0, null))
                 };
             e.onerror = function() {
                 Tg(e, !0, g);
                 var a = new Ug(Wg, "Error while loading script " + d);
                 fg(f);
                 gg(f, !1, a)
             };
             a = b.attributes || {};
             ab(a, {
                 type: "text/javascript",
                 charset: "UTF-8",
                 src: d
             });
             zc(e, a);
             Xg(c).appendChild(e);
             return f
         }
 
         function Xg(a) {
             var b = (a || document).getElementsByTagName(String(Gb));
             return b && 0 != b.length ? b[0] : a.documentElement
         }
 
         function Sg() {
             if (this && this.gd) {
                 var a = this.gd;
                 a && a.tagName == Ub &&
                     Tg(a, !0, this.pd)
             }
         }
 
         function Tg(a, b, c) {
             null != c && l.clearTimeout(c);
             a.onload = aa;
             a.onerror = aa;
             a.onreadystatechange = aa;
             b && window.setTimeout(function() {
                 Ec(a)
             }, 0)
         }
         var Wg = 0,
             Vg = 1;
 
         function Ug(a, b) {
             var c = "Jsloader error (code #" + a + ")";
             b && (c += ": " + b);
             pa.call(this, c);
             this.code = a
         }
         r(Ug, pa);
 
         function Yg(a) {
             this.Ib = a
         }
         Yg.prototype.set = function(a, b) {
             m(b) ? this.Ib.set(a, nf(b)) : this.Ib.remove(a)
         };
         Yg.prototype.get = function(a) {
             var b;
             try {
                 b = this.Ib.get(a)
             } catch (c) {
                 return
             }
             if (null !== b) try {
                 return mf(b)
             } catch (c$3) {
                 throw "Storage: Invalid value was encountered";
             }
         };
         Yg.prototype.remove = function(a) {
             this.Ib.remove(a)
         };
 
         function Zg() {}
 
         function $g() {}
         r($g, Zg);
         $g.prototype.clear = function() {
             var a = Pc(this.va(!0)),
                 b = this;
             Fa(a, function(a) {
                 b.remove(a)
             })
         };
 
         function ah(a) {
             this.T = a
         }
         r(ah, $g);
 
         function bh(a) {
             if (!a.T) return !1;
             try {
                 return a.T.setItem("__sak", "1"), a.T.removeItem("__sak"), !0
             } catch (b) {
                 return !1
             }
         }
         h = ah.prototype;
         h.set = function(a, b) {
             try {
                 this.T.setItem(a, b)
             } catch (c) {
                 if (0 == this.T.length) throw "Storage mechanism: Storage disabled";
                 throw "Storage mechanism: Quota exceeded";
             }
         };
         h.get = function(a) {
             a = this.T.getItem(a);
             if (!n(a) && null !== a) throw "Storage mechanism: Invalid value was encountered";
             return a
         };
         h.remove = function(a) {
             this.T.removeItem(a)
         };
         h.va = function(a) {
             var b = 0,
                 c = this.T,
                 d = new Mc;
             d.next = function() {
                 if (b >= c.length) throw Lc;
                 var d = c.key(b++);
                 if (a) return d;
                 d = c.getItem(d);
                 if (!n(d)) throw "Storage mechanism: Invalid value was encountered";
                 return d
             };
             return d
         };
         h.clear = function() {
             this.T.clear()
         };
         h.key = function(a) {
             return this.T.key(a)
         };
 
         function ch() {
             var a = null;
             try {
                 a = window.localStorage ||
                     null
             } catch (b) {}
             this.T = a
         }
         r(ch, ah);
 
         function dh() {
             var a = null;
             try {
                 a = window.sessionStorage || null
             } catch (b) {}
             this.T = a
         }
         r(dh, ah);
 
         function eh(a, b) {
             this.ib = a;
             this.ta = b + "::"
         }
         r(eh, $g);
         eh.prototype.set = function(a, b) {
             this.ib.set(this.ta + a, b)
         };
         eh.prototype.get = function(a) {
             return this.ib.get(this.ta + a)
         };
         eh.prototype.remove = function(a) {
             this.ib.remove(this.ta + a)
         };
         eh.prototype.va = function(a) {
             var b = this.ib.va(!0),
                 c = this,
                 d = new Mc;
             d.next = function() {
                 for (var d = b.next(); d.substr(0, c.ta.length) != c.ta;) d = b.next();
                 return a ? d.substr(c.ta.length) :
                     c.ib.get(d)
             };
             return d
         };
 
         function fh(a) {
             this.M = void 0;
             this.G = {};
             if (a) {
                 var b = Jc(a);
                 a = Ic(a);
                 for (var c = 0; c < b.length; c++) this.set(b[c], a[c])
             }
         }
         h = fh.prototype;
         h.set = function(a, b) {
             gh(this, a, b, !1)
         };
         h.add = function(a, b) {
             gh(this, a, b, !0)
         };
 
         function gh(a, b, c, d) {
             for (var e = 0; e < b.length; e++) {
                 var f = b.charAt(e);
                 a.G[f] || (a.G[f] = new fh);
                 a = a.G[f]
             }
             if (d && void 0 !== a.M) throw Error('The collection already contains the key "' + b + '"');
             a.M = c
         }
         h.get = function(a) {
             a: {
                 for (var b = this, c = 0; c < a.length; c++)
                     if (b = b.G[a.charAt(c)], !b) {
                         a = void 0;
                         break a
                     } a = b
             }
             return a ? a.M : void 0
         };
         h.R = function() {
             var a = [];
             hh(this, a);
             return a
         };
 
         function hh(a, b) {
             void 0 !== a.M && b.push(a.M);
             for (var c in a.G) hh(a.G[c], b)
         }
         h.ga = function(a) {
             var b = [];
             if (a) {
                 for (var c = this, d = 0; d < a.length; d++) {
                     var e = a.charAt(d);
                     if (!c.G[e]) return [];
                     c = c.G[e]
                 }
                 ih(c, a, b)
             } else ih(this, "", b);
             return b
         };
 
         function ih(a, b, c) {
             void 0 !== a.M && c.push(b);
             for (var d in a.G) ih(a.G[d], b + d, c)
         }
         h.La = function(a) {
             return void 0 !== this.get(a)
         };
         h.clear = function() {
             this.G = {};
             this.M = void 0
         };
         h.remove = function(a) {
             for (var b =
                     this, c = [], d = 0; d < a.length; d++) {
                 var e = a.charAt(d);
                 if (!b.G[e]) throw Error('The collection does not have the key "' + a + '"');
                 c.push([b, e]);
                 b = b.G[e]
             }
             a = b.M;
             for (delete b.M; 0 < c.length;)
                 if (e = c.pop(), b = e[0], e = e[1], b.G[e].Fb()) delete b.G[e];
                 else break;
             return a
         };
         h.clone = function() {
             return new fh(this)
         };
         h.Fb = function() {
             var a;
             if (a = void 0 === this.M) a: {
                 a = this.G;
                 for (var b in a) {
                     a = !1;
                     break a
                 }
                 a = !0
             }
             return a
         };
 
         function jh(a) {
             a = a || {};
             var b = a.email,
                 c = a.disabled;
             return B('<div class="firebaseui-textfield mdl-textfield mdl-js-textfield mdl-textfield--floating-label"><label class="mdl-textfield__label firebaseui-label" for="email">' +
                 (a.Pe ? "Enter new email address" : "Email") + '</label><input type="email" name="email" autocomplete="username" class="mdl-textfield__input firebaseui-input firebaseui-id-email" value="' + Id(null != b ? b : "") + '"' + (c ? "disabled" : "") + '></div><div class="firebaseui-error-wrapper"><p class="firebaseui-error firebaseui-text-input-error firebaseui-hidden firebaseui-id-email-error"></p></div>')
         }
 
         function H(a) {
             a = a || {};
             a = a.label;
             return B('<button type="submit" class="firebaseui-id-submit firebaseui-button mdl-button mdl-js-button mdl-button--raised mdl-button--colored">' +
                 (a ? A(a) : "Next") + "</button>")
         }
 
         function kh(a) {
             a = a || {};
             a = a.label;
             return B('<div class="firebaseui-new-password-component"><div class="firebaseui-textfield mdl-textfield mdl-js-textfield mdl-textfield--floating-label"><label class="mdl-textfield__label firebaseui-label" for="newPassword">' + (a ? A(a) : "Choose password") + '</label><input type="password" name="newPassword" autocomplete="new-password" class="mdl-textfield__input firebaseui-input firebaseui-id-new-password"></div><a href="javascript:void(0)" class="firebaseui-input-floating-button firebaseui-id-password-toggle firebaseui-input-toggle-on firebaseui-input-toggle-blur"></a><div class="firebaseui-error-wrapper"><p class="firebaseui-error firebaseui-text-input-error firebaseui-hidden firebaseui-id-new-password-error"></p></div></div>')
         }
 
         function lh() {
             var a;
             a = {};
             return B('<div class="firebaseui-textfield mdl-textfield mdl-js-textfield mdl-textfield--floating-label"><label class="mdl-textfield__label firebaseui-label" for="password">' + (a.current ? "Current password" : "Password") + '</label><input type="password" name="password" autocomplete="current-password" class="mdl-textfield__input firebaseui-input firebaseui-id-password"></div><div class="firebaseui-error-wrapper"><p class="firebaseui-error firebaseui-text-input-error firebaseui-hidden firebaseui-id-password-error"></p></div>')
         }
 
         function mh() {
             return B('<a class="firebaseui-link firebaseui-id-secondary-link" href="javascript:void(0)">Trouble signing in?</a>')
         }
 
         function nh() {
             return B('')
         }
 
         function oh(a) {
             return B('<div class="firebaseui-info-bar firebaseui-id-info-bar"><p class="firebaseui-info-bar-message">' + A(a.message) + '&nbsp;&nbsp;<a href="javascript:void(0)" class="firebaseui-link firebaseui-id-dismiss-info-bar">Dismiss</a></p></div>')
         }
         oh.B = "firebaseui.auth.soy2.element.infoBar";
 
         function ph(a) {
             var b = a.content;
             a = a.Dd;
             return B('<dialog class="mdl-dialog firebaseui-dialog firebaseui-id-dialog' + (a ? " " + Id(a) : "") + '">' + A(b) + "</dialog>")
         }
 
         function qh(a) {
             var b = a.message;
             return B(ph({
                 content: Hd('<div class="firebaseui-dialog-icon-wrapper"><div class="' + Id(a.Cb) + ' firebaseui-dialog-icon"></div></div><div class="firebaseui-progress-dialog-message">' + A(b) + "</div>")
             }))
         }
         qh.B = "firebaseui.auth.soy2.element.progressDialog";
 
         function rh(a) {
             var b = '<div class="firebaseui-list-box-actions">';
             a = a.items;
             for (var c = a.length, d = 0; d < c; d++) var e = a[d],
                 b = b + ('<button type="button" data-listboxid="' + Id(e.id) + '" class="mdl-button firebaseui-id-list-box-dialog-button firebaseui-list-box-dialog-button">' + (e.Cb ? '<div class="firebaseui-list-box-icon-wrapper"><div class="firebaseui-list-box-icon ' + Id(e.Cb) + '"></div></div>' : "") + '<div class="firebaseui-list-box-label-wrapper">' + A(e.label) + "</div></button>");
             b = "" + ph({
                 Dd: Gd("firebaseui-list-box-dialog"),
                 content: Hd(b + "</div>")
             });
             return B(b)
         }
         rh.B = "firebaseui.auth.soy2.element.listBoxDialog";
 
         function sh() {
             return B('<div class="mdl-progress mdl-js-progress mdl-progress__indeterminate firebaseui-busy-indicator firebaseui-id-busy-indicator"></div>')
         }
         sh.B = "firebaseui.auth.soy2.element.busyIndicator";
 
         function th(a) {
             a = a || {};
             var b = "";
             switch (a.providerId) {
                 case "google.com":
                     b += "Google";
                     break;
                 case "github.com":
                     b += "Github";
                     break;
                 case "facebook.com":
                     b += "Facebook";
                     break;
                 case "twitter.com":
                     b += "Twitter";
                     break;
                 default:
                     b += "Password"
             }
             return D(b)
         }
 
         function uh(a) {
             a = a || {};
             var b = a.Gd;
             return B('<div class="mdl-card mdl-shadow--2dp firebaseui-container firebaseui-id-page-sign-in"><form onsubmit="return false;"><div class="firebaseui-card-header"><h1 class="firebaseui-title">Sign in with email</h1></div><div class="firebaseui-card-content"><div class="firebaseui-relative-wrapper">' +
                 jh(a) + '</div></div><div class="firebaseui-card-actions">' + (b ? nh() : "") + H(null) + "</div></form></div>")
         }
         uh.B = "firebaseui.auth.soy2.page.signIn";
 
         function vh(a) {
             return B('<div class="mdl-card mdl-shadow--2dp firebaseui-container firebaseui-id-page-password-sign-in"><form onsubmit="return false;"><div class="firebaseui-card-header"><h1 class="firebaseui-title">Sign in</h1></div><div class="firebaseui-card-content">' + jh(a) + lh() + '</div><div class="firebaseui-card-actions"><div class="firebaseui-form-links">' +
                 mh() + '</div><div class="firebaseui-form-actions">' + B(H({
                     label: Gd("Sign In")
                 })) + "</div></div></form></div>")
         }
         vh.B = "firebaseui.auth.soy2.page.passwordSignIn";
 
         function wh(a) {
             a = a || {};
             var b = a.pe,
                 c = a.ob,
                 d = a.Yb,
                 e = B,
                 f = '<div class="mdl-card mdl-shadow--2dp firebaseui-container firebaseui-id-page-password-sign-up"><form onsubmit="return false;"><div class="firebaseui-card-header"><h1 class="firebaseui-title">Create account</h1></div><div class="firebaseui-card-content">' + jh(a);
             b ? (b = a || {}, b = b.name, b = B('<div class="firebaseui-textfield mdl-textfield mdl-js-textfield mdl-textfield--floating-label"><label class="mdl-textfield__label firebaseui-label" for="name">First &amp; last name</label><input type="text" name="name" autocomplete="name" class="mdl-textfield__input firebaseui-input firebaseui-id-name" value="' +
                 Id(null != b ? b : "") + '"></div><div class="firebaseui-error-wrapper"><p class="firebaseui-error firebaseui-text-input-error firebaseui-hidden firebaseui-id-name-error"></p></div>')) : b = "";
             f = f + b + kh({
                 Qe: !0
             });
             c ? (a = a || {}, a = B('<p class="firebaseui-tos">By tapping SAVE, you are indicating that you agree to the <a href="' + Id(Nd(a.ob)) + '" class="firebaseui-link" target="_blank">Terms of Service</a></p>')) : a = "";
             return e(f + a + '</div><div class="firebaseui-card-actions"><div class="firebaseui-form-actions">' + (d ? nh() :
                 "") + B(H({
                 label: Gd("Save")
             })) + "</div></div></form></div>")
         }
         wh.B = "firebaseui.auth.soy2.page.passwordSignUp";
 
         function xh(a) {
             a = a || {};
             var b = a.Yb;
             return B('<div class="mdl-card mdl-shadow--2dp firebaseui-container firebaseui-id-page-password-recovery"><form onsubmit="return false;"><div class="firebaseui-card-header"><h1 class="firebaseui-title">Recover password</h1></div><div class="firebaseui-card-content"><p class="firebaseui-text">Get instructions sent to this email that explain how to reset your password</p>' +
                 jh(a) + '</div><div class="firebaseui-card-actions"><div class="firebaseui-form-actions">' + (b ? nh() : "") + H({
                     label: Gd("Send")
                 }) + "</div></div></form></div>")
         }
         xh.B = "firebaseui.auth.soy2.page.passwordRecovery";
 
         function yh(a) {
             var b = a.N;
             return B('<div class="mdl-card mdl-shadow--2dp firebaseui-container firebaseui-id-page-password-recovery-email-sent"><div class="firebaseui-card-header"><h1 class="firebaseui-title">Check your email</h1></div><div class="firebaseui-card-content"><p class="firebaseui-text">Follow the instructions sent to <strong>' +
                 A(a.email) + '</strong> to recover your password</p></div><div class="firebaseui-card-actions">' + (b ? '<div class="firebaseui-form-actions">' + H({
                     label: Gd("Done")
                 }) + "</div>" : "") + "</div></div>")
         }
         yh.B = "firebaseui.auth.soy2.page.passwordRecoveryEmailSent";
 
         function zh() {
             return B('<div class="mdl-card mdl-shadow--2dp firebaseui-container firebaseui-id-page-callback"><div class="firebaseui-callback-indicator-container">' + sh() + "</div></div>")
         }
         zh.B = "firebaseui.auth.soy2.page.callback";
 
         function Ah(a) {
             return B('<div class="mdl-card mdl-shadow--2dp firebaseui-container firebaseui-id-page-password-linking"><form onsubmit="return false;"><div class="firebaseui-card-header"><h1 class="firebaseui-title">Sign in</h1></div><div class="firebaseui-card-content"><h2 class="firebaseui-subtitle">You already have an account</h2><p class="firebaseui-text">You\u2019ve already used <strong>' +
                 A(a.email) + "</strong> to sign in. Enter your password for that account.</p>" + lh() + '</div><div class="firebaseui-card-actions"><div class="firebaseui-form-links">' + mh() + '</div><div class="firebaseui-form-actions">' + B(H({
                     label: Gd("Sign In")
                 })) + "</div></div></form></div>")
         }
         Ah.B = "firebaseui.auth.soy2.page.passwordLinking";
 
         function Bh(a) {
             var b = a.email;
             a = "" + th(a);
             a = Gd(a);
             b = "" + ('<div class="mdl-card mdl-shadow--2dp firebaseui-container firebaseui-id-page-federated-linking"><form onsubmit="return false;"><div class="firebaseui-card-header"><h1 class="firebaseui-title">Sign in</h1></div><div class="firebaseui-card-content"><h2 class="firebaseui-subtitle">You already have an account</h2><p class="firebaseui-text">You\u2019ve already used <strong>' +
                 A(b) + "</strong>. Sign in with " + A(a) + ' to continue.</p></div><div class="firebaseui-card-actions"><div class="firebaseui-form-actions">' + H({
                     label: Gd("Sign in with " + a)
                 }) + "</div></div></form></div>");
             return B(b)
         }
         Bh.B = "firebaseui.auth.soy2.page.federatedLinking";
 
         function Ch(a) {
             return B('<div class="mdl-card mdl-shadow--2dp firebaseui-container firebaseui-id-page-password-reset"><form onsubmit="return false;"><div class="firebaseui-card-header"><h1 class="firebaseui-title">Reset your password</h1></div><div class="firebaseui-card-content"><p class="firebaseui-text">for <strong>' +
                 A(a.email) + "</strong></p>" + kh(Fd(a)) + '</div><div class="firebaseui-card-actions"><div class="firebaseui-form-actions">' + B(H({
                     label: Gd("Save")
                 })) + "</div></div></form></div>")
         }
         Ch.B = "firebaseui.auth.soy2.page.passwordReset";
 
         function Dh(a) {
             a = a || {};
             return B('<div class="mdl-card mdl-shadow--2dp firebaseui-container firebaseui-id-page-password-reset-success"><div class="firebaseui-card-header"><h1 class="firebaseui-title">Password changed</h1></div><div class="firebaseui-card-content"><p class="firebaseui-text">You can now sign in with your new password</p></div><div class="firebaseui-card-actions">' +
                 (a.N ? '<div class="firebaseui-form-actions">' + H(null) + "</div>" : "") + "</div></div>")
         }
         Dh.B = "firebaseui.auth.soy2.page.passwordResetSuccess";
 
         function Eh(a) {
             a = a || {};
             return B('<div class="mdl-card mdl-shadow--2dp firebaseui-container firebaseui-id-page-password-reset-failure"><div class="firebaseui-card-header"><h1 class="firebaseui-title">Try resetting your password again</h1></div><div class="firebaseui-card-content"><p class="firebaseui-text">Your request to reset your password has expired or the link has already been used</p></div><div class="firebaseui-card-actions">' +
                 (a.N ? '<div class="firebaseui-form-actions">' + H(null) + "</div>" : "") + "</div></div>")
         }
         Eh.B = "firebaseui.auth.soy2.page.passwordResetFailure";
 
         function Fh(a) {
             var b = a.N;
             return B('<div class="mdl-card mdl-shadow--2dp firebaseui-container firebaseui-id-page-email-change-revoke-success"><form onsubmit="return false;"><div class="firebaseui-card-header"><h1 class="firebaseui-title">Updated email address</h1></div><div class="firebaseui-card-content"><p class="firebaseui-text">Your sign-in email address has been changed back to <strong>' +
                 A(a.email) + '</strong>.</p><p class="firebaseui-text">If you didn\u2019t ask to change your sign-in email, it\u2019s possible someone is trying to access your account and you should <a class="firebaseui-link firebaseui-id-reset-password-link" href="javascript:void(0)">change your password right away</a>.</p></div><div class="firebaseui-card-actions">' + (b ? '<div class="firebaseui-form-actions">' + H(null) + "</div>" : "") + "</div></form></div>")
         }
         Fh.B = "firebaseui.auth.soy2.page.emailChangeRevokeSuccess";
 
         function Gh(a) {
             a =
                 a || {};
             return B('<div class="mdl-card mdl-shadow--2dp firebaseui-container firebaseui-id-page-email-change-revoke-failure"><div class="firebaseui-card-header"><h1 class="firebaseui-title">Unable to update your email address</h1></div><div class="firebaseui-card-content"><p class="firebaseui-text">There was a problem changing your sign-in email back.</p><p class="firebaseui-text">If you try again and still can\u2019t reset your email, try asking your administrator for help.</p></div><div class="firebaseui-card-actions">' +
                 (a.N ? '<div class="firebaseui-form-actions">' + H(null) + "</div>" : "") + "</div></div>")
         }
         Gh.B = "firebaseui.auth.soy2.page.emailChangeRevokeFailure";
 
         function Hh(a) {
             a = a || {};
             return B('<div class="mdl-card mdl-shadow--2dp firebaseui-container firebaseui-id-page-email-verification-success"><div class="firebaseui-card-header"><h1 class="firebaseui-title">Your email has been verified</h1></div><div class="firebaseui-card-content"><p class="firebaseui-text">You can now sign in with your new account</p></div><div class="firebaseui-card-actions">' +
                 (a.N ? '<div class="firebaseui-form-actions">' + H(null) + "</div>" : "") + "</div></div>")
         }
         Hh.B = "firebaseui.auth.soy2.page.emailVerificationSuccess";
 
         function Ih(a) {
             a = a || {};
             return B('<div class="mdl-card mdl-shadow--2dp firebaseui-container firebaseui-id-page-email-verification-failure"><div class="firebaseui-card-header"><h1 class="firebaseui-title">Try verifying your email again</h1></div><div class="firebaseui-card-content"><p class="firebaseui-text">Your request to verify your email has expired or the link has already been used</p></div><div class="firebaseui-card-actions">' +
                 (a.N ? '<div class="firebaseui-form-actions">' + H(null) + "</div>" : "") + "</div></div>")
         }
         Ih.B = "firebaseui.auth.soy2.page.emailVerificationFailure";
 
         function Jh(a) {
             return B('<div class="mdl-card mdl-shadow--2dp firebaseui-container firebaseui-id-page-unrecoverable-error"><div class="firebaseui-card-header"><h1 class="firebaseui-title">Error encountered</h1></div><div class="firebaseui-card-content"><p class="firebaseui-text">' + A(a.Kd) + "</p></div></div>")
         }
         Jh.B = "firebaseui.auth.soy2.page.unrecoverableError";
 
         function Kh(a) {
             var b = a.ke;
             return B('<div class="mdl-card mdl-shadow--2dp firebaseui-container firebaseui-id-page-email-mismatch"><form onsubmit="return false;"><div class="firebaseui-card-header"><h1 class="firebaseui-title">Sign in</h1></div><div class="firebaseui-card-content"><h2 class="firebaseui-subtitle">Continue with ' + A(a.Ee) + '?</h2><p class="firebaseui-text">You originally wanted to sign in with ' + A(b) + '</p></div><div class="firebaseui-card-actions"><div class="firebaseui-form-actions">' +
                 nh() + H({
                     label: Gd("Continue")
                 }) + "</div></div></form></div>")
         }
         Kh.B = "firebaseui.auth.soy2.page.emailMismatch";
 
         function Lh(a, b, c) {
             var d = '<div class="firebaseui-container firebaseui-page-provider-sign-in firebaseui-id-page-provider-sign-in"><div class="firebaseui-card-content"><form onsubmit="return false;"><ul class="firebaseui-idp-list">';
             a = a.ne;
             b = a.length;
             for (var e = 0; e < b; e++) {
                 var f;
                 f = {
                     providerId: a[e]
                 };
                 var g = c,
                     k = f.providerId,
                     C = f,
                     C = C || {},
                     P = "";
                 switch (C.providerId) {
                     case "google.com":
                         P += "firebaseui-idp-google";
                         break;
                     case "github.com":
                         P += "firebaseui-idp-github";
                         break;
                     case "facebook.com":
                         P += "firebaseui-idp-facebook";
                         break;
                     case "twitter.com":
                         P += "firebaseui-idp-twitter";
                         break;
                     case "phone":
                         P += "firebaseui-idp-phone";
                         break;
                     default:
                         P += "firebaseui-idp-password"
                 }
                 var C = B,
                     P = '<button class="firebaseui-idp-button mdl-button mdl-js-button mdl-button--raised ' + Id(D(P)) + ' firebaseui-id-idp-button" data-provider-id="' + Id(k) + '"><span class="firebaseui-idp-icon-wrapper"><img class="firebaseui-idp-icon" src="',
                     oc = f,
                     oc = oc || {},
                     ib = "";
                 switch (oc.providerId) {
                     case "google.com":
                         ib += Nd(g.Wd);
                         break;
                     case "github.com":
                         ib += Nd(g.Vd);
                         break;
                     case "facebook.com":
                         ib += Nd(g.Nd);
                         break;
                     case "twitter.com":
                         ib += Nd(g.Be);
                         break;
                     case "phone":
                         ib += Nd(g.le);
                         break;
                     default:
                         ib += Nd(g.je)
                 }
                 g = Ed(ib);
                 f = C(P + Id(Nd(g)) + '"></span>' + ("password" == k ? '<span class="firebaseui-idp-text firebaseui-idp-text-long">Sign in with email</span><span class="firebaseui-idp-text firebaseui-idp-text-short">Email</span>' : "phone" == k ? '<span class="firebaseui-idp-text firebaseui-idp-text-long">Sign in with phone</span><span class="firebaseui-idp-text firebaseui-idp-text-short">Phone</span>' :
                     '<span class="firebaseui-idp-text firebaseui-idp-text-long">Sign in with ' + A(th(f)) + '</span><span class="firebaseui-idp-text firebaseui-idp-text-short">' + A(th(f)) + "</span>") + "</button>");
                 d += '<li class="firebaseui-list-item">' + f + "</li>"
             }
             return B(d + "</ul></form></div></div>")
         }
         Lh.B = "firebaseui.auth.soy2.page.providerSignIn";
 
         function Mh(a) {
             a = a || {};
             var b = a.Id,
                 c = B;
             a = a || {};
             a = a.Jb;
             a = B('<div class="firebaseui-phone-number"><button class="firebaseui-id-country-selector firebaseui-country-selector mdl-button mdl-js-button"><span class="firebaseui-flag firebaseui-country-selector-flag firebaseui-id-country-selector-flag"></span><span class="firebaseui-id-country-selector-code"></span></button><div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label firebaseui-textfield firebaseui-phone-input-wrapper"><label class="mdl-textfield__label firebaseui-label" for="phoneNumber">Phone number</label><input type="tel" name="phoneNumber" class="mdl-textfield__input firebaseui-input firebaseui-id-phone-number" value="' +
                 Id(null != a ? a : "") + '"></div></div><div class="firebaseui-error-wrapper"><p class="firebaseui-error firebaseui-text-input-error firebaseui-hidden firebaseui-phone-number-error firebaseui-id-phone-number-error"></p></div>');
             return c('<div class="mdl-card firebaseui-container firebaseui-id-page-phone-sign-in-start"><form onsubmit="return false;"><div class="firebaseui-card-content"><div class="firebaseui-relative-wrapper">' +
                 a + (b ? B('<div class="firebaseui-recaptcha-wrapper"><div class="firebaseui-recaptcha-container"></div><div class="firebaseui-error-wrapper firebaseui-recaptcha-error-wrapper"><p class="firebaseui-error firebaseui-hidden firebaseui-id-recaptcha-error"></p></div></div>') : "") + '</div></div><div class="firebaseui-card-actions"><div class="firebaseui-form-actions">' + nh() + H({
                     label: Gd("Connect to Wi-Fi")
                 }) + '</div></div><div class="firebaseui-card-footer">' + "</div></form></div>")
         }
         Mh.B = "firebaseui.auth.soy2.page.phoneSignInStart";
 
         function Nh(a) {
             a = a || {};
             var b = a.ob,
                 c = B,
                 d = '<div class="mdl-card mdl-shadow--2dp firebaseui-container firebaseui-id-page-phone-sign-in-finish"><form onsubmit="return false;"><div class="firebaseui-card-header"><h1 class="firebaseui-title">Verify your phone number</h1></div><div class="firebaseui-card-content"><p class="firebaseui-text">Enter the 6-digit code we sent to <a class="firebaseui-link firebaseui-change-phone-number-link firebaseui-id-change-phone-number-link" href="javascript:void(0)">&lrm;' +
                 A(a.phoneNumber) + "</a></p>" + B('<div class="firebaseui-textfield mdl-textfield mdl-js-textfield mdl-textfield--floating-label"><label class="mdl-textfield__label firebaseui-label" for="phoneConfirmationCode">6-digit code</label><input type="number" name="phoneConfirmationCode" class="mdl-textfield__input firebaseui-input firebaseui-id-phone-confirmation-code"></div><div class="firebaseui-error-wrapper"><p class="firebaseui-error firebaseui-text-input-error firebaseui-hidden firebaseui-id-phone-confirmation-code-error"></p></div>') +
                 '</div><div class="firebaseui-card-actions"><div class="firebaseui-form-actions">' + nh() + H({
                     label: Gd("Continue")
                 }) + "</div></div>";
             b ? (a = a || {}, a = '' + B('') + "") : a = "";
             return c(d + a + "</form>" + B('<div class="firebaseui-resend-container"><span class="firebaseui-id-resend-countdown"></span><a href="javascript:void(0)" class="firebaseui-id-resend-link firebaseui-hidden firebaseui-link">Resend</a></div>') +
                 "</div>")
         }
         Nh.B = "firebaseui.auth.soy2.page.phoneSignInFinish";
 
         function Oh() {
             return D("Enter a valid phone number")
         }
 
         function Ph() {
             return D("This email already exists without any means of sign-in. Please reset the password to recover.")
         }
 
         function Qh() {
             return D("Please login again to perform this operation")
         }
 
         function Rh(a) {
             this.Za = a;
             this.Dc = new fh;
             for (a = 0; a < this.Za.length; a++) {
                 var b = this.Dc.get("+" + this.Za[a].a);
                 b ? b.push(this.Za[a]) : this.Dc.add("+" + this.Za[a].a, [this.Za[a]])
             }
         }
         Rh.prototype.search = function(a) {
             var b =
                 this.Dc,
                 c = {},
                 d = 0;
             void 0 !== b.M && (c[d] = b.M);
             for (; d < a.length; d++) {
                 var e = a.charAt(d);
                 if (!(e in b.G)) break;
                 b = b.G[e];
                 void 0 !== b.M && (c[d] = b.M)
             }
             for (var f in c)
                 if (c.hasOwnProperty(f)) return c[f];
             return []
         };
 
         function Sh(a) {
             for (var b = 0; b < Th.length; b++)
               //   if (Th[b].b === a) return Th[b];
               if (Th[b].a == 20) return Th[b];
             return null
         }
         var Th = [{
                 name: "Afghanistan",
                 b: "93-AF-0",
                 a: "93",
                 c: "AF"
             }, {
                 name: "\u00c5land Islands",
                 b: "358-AX-0",
                 a: "358",
                 c: "AX"
             }, {
                 name: "Albania",
                 b: "355-AL-0",
                 a: "355",
                 c: "AL"
             }, {
                 name: "Algeria",
                 b: "213-DZ-0",
                 a: "213",
                 c: "DZ"
             }, {
                 name: "American Samoa",
                 b: "1-AS-0",
                 a: "1",
                 c: "AS"
             }, {
                 name: "Andorra",
                 b: "376-AD-0",
                 a: "376",
                 c: "AD"
             }, {
                 name: "Angola",
                 b: "244-AO-0",
                 a: "244",
                 c: "AO"
             }, {
                 name: "Anguilla",
                 b: "1-AI-0",
                 a: "1",
                 c: "AI"
             }, {
                 name: "Antigua and Barbuda",
                 b: "1-AG-0",
                 a: "1",
                 c: "AG"
             }, {
                 name: "Argentina",
                 b: "54-AR-0",
                 a: "54",
                 c: "AR"
             }, {
                 name: "Armenia",
                 b: "374-AM-0",
                 a: "374",
                 c: "AM"
             }, {
                 name: "Aruba",
                 b: "297-AW-0",
                 a: "297",
                 c: "AW"
             }, {
                 name: "Ascension Island",
                 b: "247-AC-0",
                 a: "247",
                 c: "AC"
             }, {
                 name: "Australia",
                 b: "61-AU-0",
                 a: "61",
                 c: "AU"
             }, {
                 name: "Austria",
                 b: "43-AT-0",
                 a: "43",
                 c: "AT"
             }, {
                 name: "Azerbaijan",
                 b: "994-AZ-0",
                 a: "994",
                 c: "AZ"
             }, {
                 name: "Bahamas",
                 b: "1-BS-0",
                 a: "1",
                 c: "BS"
             }, {
                 name: "Bahrain",
                 b: "973-BH-0",
                 a: "973",
                 c: "BH"
             }, {
                 name: "Bangladesh",
                 b: "880-BD-0",
                 a: "880",
                 c: "BD"
             }, {
                 name: "Barbados",
                 b: "1-BB-0",
                 a: "1",
                 c: "BB"
             }, {
                 name: "Belarus",
                 b: "375-BY-0",
                 a: "375",
                 c: "BY"
             }, {
                 name: "Belgium",
                 b: "32-BE-0",
                 a: "32",
                 c: "BE"
             }, {
                 name: "Belize",
                 b: "501-BZ-0",
                 a: "501",
                 c: "BZ"
             }, {
                 name: "Benin",
                 b: "229-BJ-0",
                 a: "229",
                 c: "BJ"
             }, {
                 name: "Bermuda",
                 b: "1-BM-0",
                 a: "1",
                 c: "BM"
             }, {
                 name: "Bhutan",
                 b: "975-BT-0",
                 a: "975",
                 c: "BT"
             }, {
                 name: "Bolivia",
                 b: "591-BO-0",
                 a: "591",
                 c: "BO"
             }, {
                 name: "Bosnia and Herzegovina",
                 b: "387-BA-0",
                 a: "387",
                 c: "BA"
             }, {
                 name: "Botswana",
                 b: "267-BW-0",
                 a: "267",
                 c: "BW"
             }, {
                 name: "Brazil",
                 b: "55-BR-0",
                 a: "55",
                 c: "BR"
             }, {
                 name: "British Indian Ocean Territory",
                 b: "246-IO-0",
                 a: "246",
                 c: "IO"
             }, {
                 name: "British Virgin Islands",
                 b: "1-VG-0",
                 a: "1",
                 c: "VG"
             }, {
                 name: "Brunei",
                 b: "673-BN-0",
                 a: "673",
                 c: "BN"
             }, {
                 name: "Bulgaria",
                 b: "359-BG-0",
                 a: "359",
                 c: "BG"
             }, {
                 name: "Burkina Faso",
                 b: "226-BF-0",
                 a: "226",
                 c: "BF"
             }, {
                 name: "Burundi",
                 b: "257-BI-0",
                 a: "257",
                 c: "BI"
             }, {
                 name: "Cambodia",
                 b: "855-KH-0",
                 a: "855",
                 c: "KH"
             }, {
                 name: "Cameroon",
                 b: "237-CM-0",
                 a: "237",
                 c: "CM"
             }, {
                 name: "Canada",
                 b: "1-CA-0",
                 a: "1",
                 c: "CA"
             }, {
                 name: "Cape Verde",
                 b: "238-CV-0",
                 a: "238",
                 c: "CV"
             }, {
                 name: "Caribbean Netherlands",
                 b: "599-BQ-0",
                 a: "599",
                 c: "BQ"
             }, {
                 name: "Cayman Islands",
                 b: "1-KY-0",
                 a: "1",
                 c: "KY"
             }, {
                 name: "Central African Republic",
                 b: "236-CF-0",
                 a: "236",
                 c: "CF"
             }, {
                 name: "Chad",
                 b: "235-TD-0",
                 a: "235",
                 c: "TD"
             }, {
                 name: "Chile",
                 b: "56-CL-0",
                 a: "56",
                 c: "CL"
             }, {
                 name: "China",
                 b: "86-CN-0",
                 a: "86",
                 c: "CN"
             }, {
                 name: "Christmas Island",
                 b: "61-CX-0",
                 a: "61",
                 c: "CX"
             }, {
                 name: "Cocos [Keeling] Islands",
                 b: "61-CC-0",
                 a: "61",
                 c: "CC"
             }, {
                 name: "Colombia",
                 b: "57-CO-0",
                 a: "57",
                 c: "CO"
             }, {
                 name: "Comoros",
                 b: "269-KM-0",
                 a: "269",
                 c: "KM"
             }, {
                 name: "Democratic Republic Congo",
                 b: "243-CD-0",
                 a: "243",
                 c: "CD"
             }, {
                 name: "Republic of Congo",
                 b: "242-CG-0",
                 a: "242",
                 c: "CG"
             }, {
                 name: "Cook Islands",
                 b: "682-CK-0",
                 a: "682",
                 c: "CK"
             }, {
                 name: "Costa Rica",
                 b: "506-CR-0",
                 a: "506",
                 c: "CR"
             }, {
                 name: "C\u00f4te d'Ivoire",
                 b: "225-CI-0",
                 a: "225",
                 c: "CI"
             }, {
                 name: "Croatia",
                 b: "385-HR-0",
                 a: "385",
                 c: "HR"
             }, {
                 name: "Cuba",
                 b: "53-CU-0",
                 a: "53",
                 c: "CU"
             }, {
                 name: "Cura\u00e7ao",
                 b: "599-CW-0",
                 a: "599",
                 c: "CW"
             }, {
                 name: "Cyprus",
                 b: "357-CY-0",
                 a: "357",
                 c: "CY"
             }, {
                 name: "Czech Republic",
                 b: "420-CZ-0",
                 a: "420",
                 c: "CZ"
             }, {
                 name: "Denmark",
                 b: "45-DK-0",
                 a: "45",
                 c: "DK"
             }, {
                 name: "Djibouti",
                 b: "253-DJ-0",
                 a: "253",
                 c: "DJ"
             }, {
                 name: "Dominica",
                 b: "1-DM-0",
                 a: "1",
                 c: "DM"
             }, {
                 name: "Dominican Republic",
                 b: "1-DO-0",
                 a: "1",
                 c: "DO"
             }, {
                 name: "East Timor",
                 b: "670-TL-0",
                 a: "670",
                 c: "TL"
             }, {
                 name: "Ecuador",
                 b: "593-EC-0",
                 a: "593",
                 c: "EC"
             }, {
                 name: "Egypt",
                 b: "20-EG-0",
                 a: "20",
                 c: "EG"
             }, {
                 name: "El Salvador",
                 b: "503-SV-0",
                 a: "503",
                 c: "SV"
             }, {
                 name: "Equatorial Guinea",
                 b: "240-GQ-0",
                 a: "240",
                 c: "GQ"
             }, {
                 name: "Eritrea",
                 b: "291-ER-0",
                 a: "291",
                 c: "ER"
             }, {
                 name: "Estonia",
                 b: "372-EE-0",
                 a: "372",
                 c: "EE"
             }, {
                 name: "Ethiopia",
                 b: "251-ET-0",
                 a: "251",
                 c: "ET"
             }, {
                 name: "Falkland Islands [Islas Malvinas]",
                 b: "500-FK-0",
                 a: "500",
                 c: "FK"
             }, {
                 name: "Faroe Islands",
                 b: "298-FO-0",
                 a: "298",
                 c: "FO"
             }, {
                 name: "Fiji",
                 b: "679-FJ-0",
                 a: "679",
                 c: "FJ"
             }, {
                 name: "Finland",
                 b: "358-FI-0",
                 a: "358",
                 c: "FI"
             }, {
                 name: "France",
                 b: "33-FR-0",
                 a: "33",
                 c: "FR"
             }, {
                 name: "French Guiana",
                 b: "594-GF-0",
                 a: "594",
                 c: "GF"
             }, {
                 name: "French Polynesia",
                 b: "689-PF-0",
                 a: "689",
                 c: "PF"
             }, {
                 name: "Gabon",
                 b: "241-GA-0",
                 a: "241",
                 c: "GA"
             }, {
                 name: "Gambia",
                 b: "220-GM-0",
                 a: "220",
                 c: "GM"
             }, {
                 name: "Georgia",
                 b: "995-GE-0",
                 a: "995",
                 c: "GE"
             }, {
                 name: "Germany",
                 b: "49-DE-0",
                 a: "49",
                 c: "DE"
             }, {
                 name: "Ghana",
                 b: "233-GH-0",
                 a: "233",
                 c: "GH"
             }, {
                 name: "Gibraltar",
                 b: "350-GI-0",
                 a: "350",
                 c: "GI"
             }, {
                 name: "Greece",
                 b: "30-GR-0",
                 a: "30",
                 c: "GR"
             }, {
                 name: "Greenland",
                 b: "299-GL-0",
                 a: "299",
                 c: "GL"
             }, {
                 name: "Grenada",
                 b: "1-GD-0",
                 a: "1",
                 c: "GD"
             }, {
                 name: "Guadeloupe",
                 b: "590-GP-0",
                 a: "590",
                 c: "GP"
             }, {
                 name: "Guam",
                 b: "1-GU-0",
                 a: "1",
                 c: "GU"
             }, {
                 name: "Guatemala",
                 b: "502-GT-0",
                 a: "502",
                 c: "GT"
             }, {
                 name: "Guernsey",
                 b: "44-GG-0",
                 a: "44",
                 c: "GG"
             }, {
                 name: "Guinea Conakry",
                 b: "224-GN-0",
                 a: "224",
                 c: "GN"
             }, {
                 name: "Guinea-Bissau",
                 b: "245-GW-0",
                 a: "245",
                 c: "GW"
             }, {
                 name: "Guyana",
                 b: "592-GY-0",
                 a: "592",
                 c: "GY"
             }, {
                 name: "Haiti",
                 b: "509-HT-0",
                 a: "509",
                 c: "HT"
             }, {
                 name: "Heard Island and McDonald Islands",
                 b: "672-HM-0",
                 a: "672",
                 c: "HM"
             }, {
                 name: "Honduras",
                 b: "504-HN-0",
                 a: "504",
                 c: "HN"
             }, {
                 name: "Hong Kong",
                 b: "852-HK-0",
                 a: "852",
                 c: "HK"
             }, {
                 name: "Hungary",
                 b: "36-HU-0",
                 a: "36",
                 c: "HU"
             }, {
                 name: "Iceland",
                 b: "354-IS-0",
                 a: "354",
                 c: "IS"
             }, {
                 name: "India",
                 b: "91-IN-0",
                 a: "91",
                 c: "IN"
             }, {
                 name: "Indonesia",
                 b: "62-ID-0",
                 a: "62",
                 c: "ID"
             }, {
                 name: "Iran",
                 b: "98-IR-0",
                 a: "98",
                 c: "IR"
             }, {
                 name: "Iraq",
                 b: "964-IQ-0",
                 a: "964",
                 c: "IQ"
             }, {
                 name: "Ireland",
                 b: "353-IE-0",
                 a: "353",
                 c: "IE"
             }, {
                 name: "Isle of Man",
                 b: "44-IM-0",
                 a: "44",
                 c: "IM"
             }, {
                 name: "Israel",
                 b: "972-IL-0",
                 a: "972",
                 c: "IL"
             }, {
                 name: "Italy",
                 b: "39-IT-0",
                 a: "39",
                 c: "IT"
             }, {
                 name: "Jamaica",
                 b: "1-JM-0",
                 a: "1",
                 c: "JM"
             }, {
                 name: "Japan",
                 b: "81-JP-0",
                 a: "81",
                 c: "JP"
             }, {
                 name: "Jersey",
                 b: "44-JE-0",
                 a: "44",
                 c: "JE"
             }, {
                 name: "Jordan",
                 b: "962-JO-0",
                 a: "962",
                 c: "JO"
             }, {
                 name: "Kazakhstan",
                 b: "7-KZ-0",
                 a: "7",
                 c: "KZ"
             }, {
                 name: "Kenya",
                 b: "254-KE-0",
                 a: "254",
                 c: "KE"
             }, {
                 name: "Kiribati",
                 b: "686-KI-0",
                 a: "686",
                 c: "KI"
             }, {
                 name: "Kosovo",
                 b: "377-XK-0",
                 a: "377",
                 c: "XK"
             }, {
                 name: "Kosovo",
                 b: "381-XK-0",
                 a: "381",
                 c: "XK"
             }, {
                 name: "Kosovo",
                 b: "386-XK-0",
                 a: "386",
                 c: "XK"
             }, {
                 name: "Kuwait",
                 b: "965-KW-0",
                 a: "965",
                 c: "KW"
             }, {
                 name: "Kyrgyzstan",
                 b: "996-KG-0",
                 a: "996",
                 c: "KG"
             }, {
                 name: "Laos",
                 b: "856-LA-0",
                 a: "856",
                 c: "LA"
             }, {
                 name: "Latvia",
                 b: "371-LV-0",
                 a: "371",
                 c: "LV"
             }, {
                 name: "Lebanon",
                 b: "961-LB-0",
                 a: "961",
                 c: "LB"
             },
             {
                 name: "Lesotho",
                 b: "266-LS-0",
                 a: "266",
                 c: "LS"
             }, {
                 name: "Liberia",
                 b: "231-LR-0",
                 a: "231",
                 c: "LR"
             }, {
                 name: "Libya",
                 b: "218-LY-0",
                 a: "218",
                 c: "LY"
             }, {
                 name: "Liechtenstein",
                 b: "423-LI-0",
                 a: "423",
                 c: "LI"
             }, {
                 name: "Lithuania",
                 b: "370-LT-0",
                 a: "370",
                 c: "LT"
             }, {
                 name: "Luxembourg",
                 b: "352-LU-0",
                 a: "352",
                 c: "LU"
             }, {
                 name: "Macau",
                 b: "853-MO-0",
                 a: "853",
                 c: "MO"
             }, {
                 name: "Macedonia",
                 b: "389-MK-0",
                 a: "389",
                 c: "MK"
             }, {
                 name: "Madagascar",
                 b: "261-MG-0",
                 a: "261",
                 c: "MG"
             }, {
                 name: "Malawi",
                 b: "265-MW-0",
                 a: "265",
                 c: "MW"
             }, {
                 name: "Malaysia",
                 b: "60-MY-0",
                 a: "60",
                 c: "MY"
             },
             {
                 name: "Maldives",
                 b: "960-MV-0",
                 a: "960",
                 c: "MV"
             }, {
                 name: "Mali",
                 b: "223-ML-0",
                 a: "223",
                 c: "ML"
             }, {
                 name: "Malta",
                 b: "356-MT-0",
                 a: "356",
                 c: "MT"
             }, {
                 name: "Marshall Islands",
                 b: "692-MH-0",
                 a: "692",
                 c: "MH"
             }, {
                 name: "Martinique",
                 b: "596-MQ-0",
                 a: "596",
                 c: "MQ"
             }, {
                 name: "Mauritania",
                 b: "222-MR-0",
                 a: "222",
                 c: "MR"
             }, {
                 name: "Mauritius",
                 b: "230-MU-0",
                 a: "230",
                 c: "MU"
             }, {
                 name: "Mayotte",
                 b: "262-YT-0",
                 a: "262",
                 c: "YT"
             }, {
                 name: "Mexico",
                 b: "52-MX-0",
                 a: "52",
                 c: "MX"
             }, {
                 name: "Micronesia",
                 b: "691-FM-0",
                 a: "691",
                 c: "FM"
             }, {
                 name: "Moldova",
                 b: "373-MD-0",
                 a: "373",
                 c: "MD"
             },
             {
                 name: "Monaco",
                 b: "377-MC-0",
                 a: "377",
                 c: "MC"
             }, {
                 name: "Mongolia",
                 b: "976-MN-0",
                 a: "976",
                 c: "MN"
             }, {
                 name: "Montenegro",
                 b: "382-ME-0",
                 a: "382",
                 c: "ME"
             }, {
                 name: "Montserrat",
                 b: "1-MS-0",
                 a: "1",
                 c: "MS"
             }, {
                 name: "Morocco",
                 b: "212-MA-0",
                 a: "212",
                 c: "MA"
             }, {
                 name: "Mozambique",
                 b: "258-MZ-0",
                 a: "258",
                 c: "MZ"
             }, {
                 name: "Myanmar [Burma]",
                 b: "95-MM-0",
                 a: "95",
                 c: "MM"
             }, {
                 name: "Namibia",
                 b: "264-NA-0",
                 a: "264",
                 c: "NA"
             }, {
                 name: "Nauru",
                 b: "674-NR-0",
                 a: "674",
                 c: "NR"
             }, {
                 name: "Nepal",
                 b: "977-NP-0",
                 a: "977",
                 c: "NP"
             }, {
                 name: "Netherlands",
                 b: "31-NL-0",
                 a: "31",
                 c: "NL"
             },
             {
                 name: "New Caledonia",
                 b: "687-NC-0",
                 a: "687",
                 c: "NC"
             }, {
                 name: "New Zealand",
                 b: "64-NZ-0",
                 a: "64",
                 c: "NZ"
             }, {
                 name: "Nicaragua",
                 b: "505-NI-0",
                 a: "505",
                 c: "NI"
             }, {
                 name: "Niger",
                 b: "227-NE-0",
                 a: "227",
                 c: "NE"
             }, {
                 name: "Nigeria",
                 b: "234-NG-0",
                 a: "234",
                 c: "NG"
             }, {
                 name: "Niue",
                 b: "683-NU-0",
                 a: "683",
                 c: "NU"
             }, {
                 name: "Norfolk Island",
                 b: "672-NF-0",
                 a: "672",
                 c: "NF"
             }, {
                 name: "North Korea",
                 b: "850-KP-0",
                 a: "850",
                 c: "KP"
             }, {
                 name: "Northern Mariana Islands",
                 b: "1-MP-0",
                 a: "1",
                 c: "MP"
             }, {
                 name: "Norway",
                 b: "47-NO-0",
                 a: "47",
                 c: "NO"
             }, {
                 name: "Oman",
                 b: "968-OM-0",
                 a: "968",
                 c: "OM"
             }, {
                 name: "Pakistan",
                 b: "92-PK-0",
                 a: "92",
                 c: "PK"
             }, {
                 name: "Palau",
                 b: "680-PW-0",
                 a: "680",
                 c: "PW"
             }, {
                 name: "Palestinian Territories",
                 b: "970-PS-0",
                 a: "970",
                 c: "PS"
             }, {
                 name: "Panama",
                 b: "507-PA-0",
                 a: "507",
                 c: "PA"
             }, {
                 name: "Papua New Guinea",
                 b: "675-PG-0",
                 a: "675",
                 c: "PG"
             }, {
                 name: "Paraguay",
                 b: "595-PY-0",
                 a: "595",
                 c: "PY"
             }, {
                 name: "Peru",
                 b: "51-PE-0",
                 a: "51",
                 c: "PE"
             }, {
                 name: "Philippines",
                 b: "63-PH-0",
                 a: "63",
                 c: "PH"
             }, {
                 name: "Poland",
                 b: "48-PL-0",
                 a: "48",
                 c: "PL"
             }, {
                 name: "Portugal",
                 b: "351-PT-0",
                 a: "351",
                 c: "PT"
             }, {
                 name: "Puerto Rico",
                 b: "1-PR-0",
                 a: "1",
                 c: "PR"
             }, {
                 name: "Qatar",
                 b: "974-QA-0",
                 a: "974",
                 c: "QA"
             }, {
                 name: "R\u00e9union",
                 b: "262-RE-0",
                 a: "262",
                 c: "RE"
             }, {
                 name: "Romania",
                 b: "40-RO-0",
                 a: "40",
                 c: "RO"
             }, {
                 name: "Russia",
                 b: "7-RU-0",
                 a: "7",
                 c: "RU"
             }, {
                 name: "Rwanda",
                 b: "250-RW-0",
                 a: "250",
                 c: "RW"
             }, {
                 name: "Saint Barth\u00e9lemy",
                 b: "590-BL-0",
                 a: "590",
                 c: "BL"
             }, {
                 name: "Saint Helena",
                 b: "290-SH-0",
                 a: "290",
                 c: "SH"
             }, {
                 name: "St. Kitts",
                 b: "1-KN-0",
                 a: "1",
                 c: "KN"
             }, {
                 name: "St. Lucia",
                 b: "1-LC-0",
                 a: "1",
                 c: "LC"
             }, {
                 name: "Saint Martin",
                 b: "590-MF-0",
                 a: "590",
                 c: "MF"
             }, {
                 name: "Saint Pierre and Miquelon",
                 b: "508-PM-0",
                 a: "508",
                 c: "PM"
             }, {
                 name: "St. Vincent",
                 b: "1-VC-0",
                 a: "1",
                 c: "VC"
             }, {
                 name: "Samoa",
                 b: "685-WS-0",
                 a: "685",
                 c: "WS"
             }, {
                 name: "San Marino",
                 b: "378-SM-0",
                 a: "378",
                 c: "SM"
             }, {
                 name: "S\u00e3o Tom\u00e9 and Pr\u00edncipe",
                 b: "239-ST-0",
                 a: "239",
                 c: "ST"
             }, {
                 name: "Saudi Arabia",
                 b: "966-SA-0",
                 a: "966",
                 c: "SA"
             }, {
                 name: "Senegal",
                 b: "221-SN-0",
                 a: "221",
                 c: "SN"
             }, {
                 name: "Serbia",
                 b: "381-RS-0",
                 a: "381",
                 c: "RS"
             }, {
                 name: "Seychelles",
                 b: "248-SC-0",
                 a: "248",
                 c: "SC"
             }, {
                 name: "Sierra Leone",
                 b: "232-SL-0",
                 a: "232",
                 c: "SL"
             }, {
                 name: "Singapore",
                 b: "65-SG-0",
                 a: "65",
                 c: "SG"
             }, {
                 name: "Sint Maarten",
                 b: "1-SX-0",
                 a: "1",
                 c: "SX"
             }, {
                 name: "Slovakia",
                 b: "421-SK-0",
                 a: "421",
                 c: "SK"
             }, {
                 name: "Slovenia",
                 b: "386-SI-0",
                 a: "386",
                 c: "SI"
             }, {
                 name: "Solomon Islands",
                 b: "677-SB-0",
                 a: "677",
                 c: "SB"
             }, {
                 name: "Somalia",
                 b: "252-SO-0",
                 a: "252",
                 c: "SO"
             }, {
                 name: "South Africa",
                 b: "27-ZA-0",
                 a: "27",
                 c: "ZA"
             }, {
                 name: "South Georgia and the South Sandwich Islands",
                 b: "500-GS-0",
                 a: "500",
                 c: "GS"
             }, {
                 name: "South Korea",
                 b: "82-KR-0",
                 a: "82",
                 c: "KR"
             }, {
                 name: "South Sudan",
                 b: "211-SS-0",
                 a: "211",
                 c: "SS"
             }, {
                 name: "Spain",
                 b: "34-ES-0",
                 a: "34",
                 c: "ES"
             }, {
                 name: "Sri Lanka",
                 b: "94-LK-0",
                 a: "94",
                 c: "LK"
             }, {
                 name: "Sudan",
                 b: "249-SD-0",
                 a: "249",
                 c: "SD"
             }, {
                 name: "Suriname",
                 b: "597-SR-0",
                 a: "597",
                 c: "SR"
             }, {
                 name: "Svalbard and Jan Mayen",
                 b: "47-SJ-0",
                 a: "47",
                 c: "SJ"
             }, {
                 name: "Swaziland",
                 b: "268-SZ-0",
                 a: "268",
                 c: "SZ"
             }, {
                 name: "Sweden",
                 b: "46-SE-0",
                 a: "46",
                 c: "SE"
             }, {
                 name: "Switzerland",
                 b: "41-CH-0",
                 a: "41",
                 c: "CH"
             }, {
                 name: "Syria",
                 b: "963-SY-0",
                 a: "963",
                 c: "SY"
             }, {
                 name: "Taiwan",
                 b: "886-TW-0",
                 a: "886",
                 c: "TW"
             }, {
                 name: "Tajikistan",
                 b: "992-TJ-0",
                 a: "992",
                 c: "TJ"
             }, {
                 name: "Tanzania",
                 b: "255-TZ-0",
                 a: "255",
                 c: "TZ"
             }, {
                 name: "Thailand",
                 b: "66-TH-0",
                 a: "66",
                 c: "TH"
             }, {
                 name: "Togo",
                 b: "228-TG-0",
                 a: "228",
                 c: "TG"
             }, {
                 name: "Tokelau",
                 b: "690-TK-0",
                 a: "690",
                 c: "TK"
             }, {
                 name: "Tonga",
                 b: "676-TO-0",
                 a: "676",
                 c: "TO"
             }, {
                 name: "Trinidad/Tobago",
                 b: "1-TT-0",
                 a: "1",
                 c: "TT"
             }, {
                 name: "Tunisia",
                 b: "216-TN-0",
                 a: "216",
                 c: "TN"
             }, {
                 name: "Turkey",
                 b: "90-TR-0",
                 a: "90",
                 c: "TR"
             }, {
                 name: "Turkmenistan",
                 b: "993-TM-0",
                 a: "993",
                 c: "TM"
             }, {
                 name: "Turks and Caicos Islands",
                 b: "1-TC-0",
                 a: "1",
                 c: "TC"
             }, {
                 name: "Tuvalu",
                 b: "688-TV-0",
                 a: "688",
                 c: "TV"
             }, {
                 name: "U.S. Virgin Islands",
                 b: "1-VI-0",
                 a: "1",
                 c: "VI"
             }, {
                 name: "Uganda",
                 b: "256-UG-0",
                 a: "256",
                 c: "UG"
             }, {
                 name: "Ukraine",
                 b: "380-UA-0",
                 a: "380",
                 c: "UA"
             }, {
                 name: "United Arab Emirates",
                 b: "971-AE-0",
                 a: "971",
                 c: "AE"
             }, {
                 name: "United Kingdom",
                 b: "44-GB-0",
                 a: "44",
                 c: "GB"
             }, {
                 name: "United States",
                 b: "1-US-0",
                 a: "1",
                 c: "US"
             }, {
                 name: "Uruguay",
                 b: "598-UY-0",
                 a: "598",
                 c: "UY"
             }, {
                 name: "Uzbekistan",
                 b: "998-UZ-0",
                 a: "998",
                 c: "UZ"
             }, {
                 name: "Vanuatu",
                 b: "678-VU-0",
                 a: "678",
                 c: "VU"
             }, {
                 name: "Vatican City",
                 b: "379-VA-0",
                 a: "379",
                 c: "VA"
             }, {
                 name: "Venezuela",
                 b: "58-VE-0",
                 a: "58",
                 c: "VE"
             },
             {
                 name: "Vietnam",
                 b: "84-VN-0",
                 a: "84",
                 c: "VN"
             }, {
                 name: "Wallis and Futuna",
                 b: "681-WF-0",
                 a: "681",
                 c: "WF"
             }, {
                 name: "Western Sahara",
                 b: "212-EH-0",
                 a: "212",
                 c: "EH"
             }, {
                 name: "Yemen",
                 b: "967-YE-0",
                 a: "967",
                 c: "YE"
             }, {
                 name: "Zambia",
                 b: "260-ZM-0",
                 a: "260",
                 c: "ZM"
             }, {
                 name: "Zimbabwe",
                 b: "263-ZW-0",
                 a: "263",
                 c: "ZW"
             }
         ];
         (function(a, b) {
             a.sort(function(a, d) {
                 return a.name.localeCompare(d.name, b)
             })
         })(Th, "en");
         var Uh = new Rh(Th);
 
         function Vh(a, b, c, d) {
             this.ab = a;
             this.Nc = b || null;
             this.me = c || null;
             this.xc = d || null
         }
         Vh.prototype.J = function() {
             return this.ab
         };
         Vh.prototype.nb = function() {
             return {
                 email: this.ab,
                 displayName: this.Nc,
                 photoUrl: this.me,
                 providerId: this.xc
             }
         };
 
         function Wh(a) {
             return a.email ? new Vh(a.email, a.displayName, a.photoUrl, a.providerId) : null
         }
         var Xh = null;
 
         function Yh(a) {
             return !(!a || -32E3 != a.code || "Service unavailable" != a.message)
         }
 
         function Zh(a, b, c, d) {
             Xh || (a = {
                     callbacks: {
                         empty: a,
                         select: function(a, d) {
                             a && a.account && b ? b(Wh(a.account)) : c && c(!Yh(d))
                         },
                         store: a,
                         update: a
                     },
                     language: "en",
                     providers: void 0,
                     ui: d
                 }, "undefined" != typeof accountchooser && accountchooser.Api &&
                 accountchooser.Api.init ? Xh = accountchooser.Api.init(a) : (Xh = new $h(a), ai()))
         }
 
         function bi(a, b, c) {
             function d() {
                 var a = ld(c).toString();
                 Xh.select(Ia(b || [], function(a) {
                     return a.nb()
                 }), {
                     clientCallbackUrl: a
                 })
             }
             b && b.length ? d() : Xh.checkEmpty(function(b, c) {
                 b || c ? a(!Yh(c)) : d()
             })
         }
 
         function $h(a) {
             this.g = a;
             this.g.callbacks = this.g.callbacks || {}
         }
 
         function ai() {
             var a = Xh;
             ga(a.g.callbacks.empty) && a.g.callbacks.empty()
         }
         var ci = {
             code: -32E3,
             message: "Service unavailable",
             data: "Service is unavailable."
         };
         h = $h.prototype;
         h.store =
             function() {
                 ga(this.g.callbacks.store) && this.g.callbacks.store(void 0, ci)
             };
         h.select = function() {
             ga(this.g.callbacks.select) && this.g.callbacks.select(void 0, ci)
         };
         h.update = function() {
             ga(this.g.callbacks.update) && this.g.callbacks.update(void 0, ci)
         };
         h.checkDisabled = function(a) {
             a(!0)
         };
         h.checkEmpty = function(a) {
             a(void 0, ci)
         };
         h.checkAccountExist = function(a, b) {
             b(void 0, ci)
         };
         h.checkShouldUpdate = function(a, b) {
             b(void 0, ci)
         };
 
         function di(a) {
             a = ha(a) && 1 == a.nodeType ? a : document.querySelector(String(a));
             if (null == a) throw Error("Could not find the FirebaseUI widget element on the page.");
             return a
         }
 
         function ei() {
             this.ca = {}
         }
         ei.prototype.define = function(a, b) {
             if (a.toLowerCase() in this.ca) throw Error("Configuration " + a + " has already been defined.");
             this.ca[a.toLowerCase()] = b
         };
         ei.prototype.update = function(a, b) {
             if (!(a.toLowerCase() in this.ca)) throw Error("Configuration " + a + " is not defined.");
             this.ca[a.toLowerCase()] = b
         };
         ei.prototype.get = function(a) {
             if (!(a.toLowerCase() in this.ca)) throw Error("Configuration " + a + " is not defined.");
             return this.ca[a.toLowerCase()]
         };
 
         function fi(a, b) {
             a = a.get(b);
             if (!a) throw Error("Configuration " + b + " is required.");
             return a
         }
         var gi = {},
             hi = 0;
 
         function ii(a, b) {
             if (!a) throw Error("Event target element must be provided!");
             a = ji(a);
             if (gi[a] && gi[a].length)
                 for (var c = 0; c < gi[a].length; c++) gi[a][c].dispatchEvent(b)
         }
 
         function ki(a) {
             var b = ji(a.L());
             gi[b] && gi[b].length && (Oa(gi[b], function(b) {
                 return b == a
             }), gi[b].length || delete gi[b])
         }
 
         function ji(a) {
             "undefined" === typeof a.Mc && (a.Mc = hi, hi++);
             return a.Mc
         }
 
         function li(a) {
             if (!a) throw Error("Event target element must be provided!");
             this.Hd = a;
             F.call(this)
         }
         r(li, F);
         li.prototype.L = function() {
             return this.Hd
         };
         li.prototype.register = function() {
             var a = ji(this.L());
             gi[a] ? La(gi[a], this) || gi[a].push(this) : gi[a] = [this]
         };
         li.prototype.unregister = function() {
             ki(this)
         };
         var mi = {
             "facebook.com": "FacebookAuthProvider",
             "github.com": "GithubAuthProvider",
             "google.com": "GoogleAuthProvider",
             password: "EmailAuthProvider",
             "twitter.com": "TwitterAuthProvider",
             phone: "PhoneAuthProvider"
         };
         var Ef;
         Ef = If("firebaseui");
         var ni = new Pf;
         if (1 != ni.Tc) {
             Hf();
             var oi = Gf,
                 pi =
                 ni.oe;
             oi.gb || (oi.gb = []);
             oi.gb.push(pi);
             ni.Tc = !0
         }
 
         function qi(a, b) {
             this.ab = a;
             this.xa = b || null
         }
         qi.prototype.J = function() {
             return this.ab
         };
         qi.prototype.nb = function() {
             return {
                 email: this.ab,
                 credential: this.xa && Za(this.xa)
             }
         };
 
         function ri(a) {
             if (a && a.email) {
                 var b;
                 if (b = a.credential) {
                     var c = (b = a.credential) && b.providerId;
                     b = mi[c] && firebase.auth[mi[c]] ? b.secret && b.accessToken ? firebase.auth[mi[c]].credential(b.accessToken, b.secret) : c == firebase.auth.GoogleAuthProvider.PROVIDER_ID ? firebase.auth[mi[c]].credential(b.idToken,
                         b.accessToken) : firebase.auth[mi[c]].credential(b.accessToken) : null
                 }
                 return new qi(a.email, b)
             }
             return null
         }
 
         function si(a, b) {
             this.ec = a;
             this.Jb = b
         }
 
         function ti(a) {
             var b = Sh(a.ec);
             if (!b) throw Error("Country ID " + a.ec + " not found.");
             return "+" + b.a + a.Jb
         }
         var ui = /MSIE ([\d.]+).*Windows NT ([\d.]+)/,
             vi = /Firefox\/([\d.]+)/,
             wi = /Opera[ \/]([\d.]+)(.*Version\/([\d.]+))?/,
             xi = /Chrome\/([\d.]+)/,
             yi = /((Windows NT ([\d.]+))|(Mac OS X ([\d_]+))).*Version\/([\d.]+).*Safari/,
             zi = /Mac OS X;.*(?!(Version)).*Safari/,
             Ai = /Android ([\d.]+).*Safari/,
             Bi = /OS ([\d_]+) like Mac OS X.*Mobile.*Safari/,
             Ci = /Konqueror\/([\d.]+)/,
             Di = /MSIE ([\d.]+).*Windows Phone OS ([\d.]+)/;
 
         function I(a, b) {
             this.Va = a;
             a = a.split(b || ".");
             this.Ya = [];
             for (b = 0; b < a.length; b++) this.Ya.push(parseInt(a[b], 10))
         }
         I.prototype.compare = function(a) {
             a instanceof I || (a = new I(String(a)));
             for (var b = Math.max(this.Ya.length, a.Ya.length), c = 0; c < b; c++) {
                 var d = this.Ya[c],
                     e = a.Ya[c];
                 if (void 0 !== d && void 0 !== e && d !== e) return d - e;
                 if (void 0 === d) return -1;
                 if (void 0 === e) return 1
             }
             return 0
         };
 
         function J(a, b) {
             return 0 <=
                 a.compare(b)
         }
 
         function Ei() {
             var a = window.navigator && window.navigator.userAgent;
             if (a) {
                 var b;
                 if (b = a.match(wi)) {
                     var c = new I(b[3] || b[1]);
                     return 0 <= a.indexOf("Opera Mini") ? !1 : 0 <= a.indexOf("Opera Mobi") ? 0 <= a.indexOf("Android") && J(c, "10.1") : J(c, "8.0")
                 }
                 if (b = a.match(vi)) return J(new I(b[1]), "2.0");
                 if (b = a.match(xi)) return J(new I(b[1]), "6.0");
                 if (b = a.match(yi)) return c = new I(b[6]), a = b[3] && new I(b[3]), b = b[5] && new I(b[5], "_"), (!(!a || !J(a, "6.0")) || !(!b || !J(b, "10.5.6"))) && J(c, "3.0");
                 if (b = a.match(Ai)) return J(new I(b[1]),
                     "3.0");
                 if (b = a.match(Bi)) return J(new I(b[1], "_"), "4.0");
                 if (b = a.match(Ci)) return J(new I(b[1]), "4.7");
                 if (b = a.match(Di)) return c = new I(b[1]), a = new I(b[2]), J(c, "7.0") && J(a, "7.0");
                 if (b = a.match(ui)) return c = new I(b[1]), a = new I(b[2]), J(c, "7.0") && J(a, "6.0");
                 if (a.match(zi)) return !1
             }
             return !0
         }
         var Fi, Gi = new ch;
         Fi = bh(Gi) ? new eh(Gi, "firebaseui") : null;
         var Hi = new Yg(Fi),
             Ii, Ji = new dh;
         Ii = bh(Ji) ? new eh(Ji, "firebaseui") : null;
         var Ki = new Yg(Ii),
             Li = {
                 name: "pendingEmailCredential",
                 Ra: !1
             },
             Mi = {
                 name: "redirectUrl",
                 Ra: !1
             },
             Ni = {
                 name: "rememberAccount",
                 Ra: !1
             },
             Oi = {
                 name: "rememberedAccounts",
                 Ra: !0
             };
 
         function Pi(a, b) {
             return (a.Ra ? Hi : Ki).get(b ? a.name + ":" + b : a.name)
         }
 
         function Qi(a, b) {
             (a.Ra ? Hi : Ki).remove(b ? a.name + ":" + b : a.name)
         }
 
         function Ri(a, b, c) {
             (a.Ra ? Hi : Ki).set(c ? a.name + ":" + c : a.name, b)
         }
 
         function Si(a, b) {
             Ri(Mi, a, b)
         }
 
         function Ti(a, b) {
             Ri(Ni, a, b)
         }
 
         function Ui(a) {
             a = Pi(Oi, a) || [];
             a = Ia(a, function(a) {
                 return Wh(a)
             });
             return Ha(a, ca)
         }
 
         function Vi(a, b) {
             var c = Ui(b),
                 d = Ka(c, function(b) {
                     return b.J() == a.J() && (b.xc || null) == (a.xc || null)
                 }); - 1 < d && Na(c, d);
             c.unshift(a);
             Ri(Oi, Ia(c, function(a) {
                 return a.nb()
             }), b)
         }
 
         function Wi(a) {
             a = Pi(Li, a) || null;
             return ri(a)
         }
 
         function Xi(a) {
             Yi(a, "upgradeElement")
         }
 
         function Zi(a) {
             Yi(a, "downgradeElements")
         }
         var $i = ["mdl-js-textfield", "mdl-js-progress", "mdl-js-spinner", "mdl-js-button"];
 
         function Yi(a, b) {
             a && window.componentHandler && window.componentHandler[b] && Fa($i, function(c) {
                 if (vg(a, c)) window.componentHandler[b](a);
                 c = wc(c, a);
                 Fa(c, function(a) {
                     window.componentHandler[b](a)
                 })
             })
         }
 
         function aj() {
             this.g = new ei;
             this.g.define("acUiConfig");
             this.g.define("callbacks");
             this.g.define("credentialHelper", bj);
             this.g.define("popupMode", !1);
             this.g.define("queryParameterForSignInSuccessUrl", "signInSuccessUrl");
             this.g.define("queryParameterForWidgetMode", "mode");
             this.g.define("signInFlow");
             this.g.define("signInOptions");
             this.g.define("signInSuccessUrl");
             this.g.define("siteName");
             this.g.define("tosUrl");
             this.g.define("widgetUrl")
         }
         var bj = "accountchooser.com",
             cj = {
                 Ge: bj,
                 NONE: "none"
             },
             dj = {
                 Ie: "popup",
                 Ke: "redirect"
             };
 
         function ej(a) {
             return a.g.get("acUiConfig") || null
         }
         var fj = {
                 He: "callback",
                 Je: "recoverEmail",
                 Le: "resetPassword",
                 Me: "select",
                 Ne: "verifyEmail"
             },
             gj = ["sitekey", "tabindex", "callback", "expired-callback"];
 
         function hj(a) {
             var b = a.g.get("widgetUrl") || window.location.href;
             return ij(a, b)
         }
 
         function ij(a, b) {
             a = jj(a);
             for (var c = b.search(Wc), d = 0, e, f = []; 0 <= (e = Vc(b, d, a, c));) f.push(b.substring(d, e)), d = Math.min(b.indexOf("&", e) + 1 || c, c);
             f.push(b.substr(d));
             b = [f.join("").replace(Yc, "$1"), "&", a];
             b.push("=", encodeURIComponent("select"));
             b[1] && (a = b[0], c = a.indexOf("#"), 0 <= c && (b.push(a.substr(c)), b[0] =
                 a = a.substr(0, c)), c = a.indexOf("?"), 0 > c ? b[1] = "?" : c == a.length - 1 && (b[1] = void 0));
             return b.join("")
         }
 
         function kj(a) {
             a = a.g.get("signInOptions") || [];
             for (var b = [], c = 0; c < a.length; c++) {
                 var d = a[c],
                     d = ha(d) ? d : {
                         provider: d
                     };
                 mi[d.provider] && b.push(d)
             }
             return b
         }
 
         function lj(a, b) {
             a = kj(a);
             for (var c = 0; c < a.length; c++)
                 if (a[c].provider === b) return a[c];
             return null
         }
 
         function mj(a) {
             return Ia(kj(a), function(a) {
                 return a.provider
             })
         }
 
         function nj(a) {
             var b = null;
             Fa(kj(a), function(a) {
                 a.provider == firebase.auth.PhoneAuthProvider.PROVIDER_ID &&
                     ha(a.recaptchaParameters) && !da(a.recaptchaParameters) && (b = Za(a.recaptchaParameters))
             });
             if (b) {
                 var c = [];
                 Fa(gj, function(a) {
                     "undefined" !== typeof b[a] && (c.push(a), delete b[a])
                 });
                 c.length && Ef && Ef.log(zf + c.join(", "), void 0)
             }
             return b
         }
 
         function oj(a) {
             a = (a = lj(a, firebase.auth.PhoneAuthProvider.PROVIDER_ID)) && a.defaultCountry || null;
             var b;
             if (b = a) {
                 a = a.toUpperCase();
                 b = [];
                 for (var c = 0; c < Th.length; c++) Th[c].c === a && b.push(Th[c])
             }
             return (a = b) && a[0] ||
                 null
         }
 
         function jj(a) {
             return fi(a.g, "queryParameterForWidgetMode")
         }
 
         function pj(a) {
             return a.g.get("tosUrl") || null
         }
 
         function qj(a) {
             return (a = lj(a, firebase.auth.EmailAuthProvider.PROVIDER_ID)) && "undefined" !== typeof a.requireDisplayName ? !!a.requireDisplayName : !0
         }
 
         function rj(a) {
             a = a.g.get("signInFlow");
             for (var b in dj)
                 if (dj[b] == a) return dj[b];
             return "redirect"
         }
 
         function sj(a) {
             return tj(a).uiShown || null
         }
 
         function tj(a) {
             return a.g.get("callbacks") || {}
         }
 
         function uj(a) {
             if ("http:" !== (window.location && window.location.protocol) &&
                 "https:" !== (window.location && window.location.protocol)) return "none";
             a = a.g.get("credentialHelper");
             for (var b in cj)
                 if (cj[b] == a) return cj[b];
             return bj
         }
         aj.prototype.Sb = function(a) {
             for (var b in a) try {
                 this.g.update(b, a[b])
             } catch (c) {
                 Ef && Df('Invalid config: "' + b + '"')
             }
             kb && this.g.update("popupMode", !1)
         };
         aj.prototype.update = function(a, b) {
             this.g.update(a, b)
         };
         var vj, wj, xj, K = {};
 
         function L(a, b, c, d) {
             K[a].apply(null, Array.prototype.slice.call(arguments, 1))
         }
 
         function M(a, b) {
             var c;
             c = Gc(a, "firebaseui-textfield");
             b ? (xg(a,
                 "firebaseui-input-invalid"), wg(a, "firebaseui-input"), c && xg(c, "firebaseui-textfield-invalid")) : (xg(a, "firebaseui-input"), wg(a, "firebaseui-input-invalid"), c && wg(c, "firebaseui-textfield-invalid"))
         }
 
         function yj(a, b, c) {
             b = new Og(b);
             Ge(a, ma(He, b));
             sg(a).ra(b, "input", c)
         }
 
         function zj(a, b, c) {
             b = new yg(b);
             Ge(a, ma(He, b));
             sg(a).ra(b, "key", function(a) {
                 13 == a.keyCode && (a.stopPropagation(), a.preventDefault(), c(a))
             })
         }
 
         function Aj(a, b, c) {
             b = new Ng(b);
             Ge(a, ma(He, b));
             sg(a).ra(b, "focusin", c)
         }
 
         function Bj(a, b, c) {
             b = new Ng(b);
             Ge(a, ma(He, b));
             sg(a).ra(b, "focusout", c)
         }
 
         function Cj(a, b, c) {
             b = new Ig(b);
             Ge(a, ma(He, b));
             sg(a).ra(b, "action", function(a) {
                 a.stopPropagation();
                 a.preventDefault();
                 c(a)
             })
         }
 
         function Dj(a) {
             wg(a, "firebaseui-hidden")
         }
 
         function N(a, b) {
             b && Fc(a, b);
             xg(a, "firebaseui-hidden")
         }
 
         function Ej(a) {
             return !vg(a, "firebaseui-hidden") && "none" != a.style.display
         }
 
         function Fj(a, b, c) {
             Gj.call(this);
             document.body.appendChild(a);
             a.showModal || window.dialogPolyfill.registerDialog(a);
             a.showModal();
             Xi(a);
             b && Cj(this, a, function(b) {
                 var c = a.getBoundingClientRect();
                 (b.clientX < c.left || c.left + c.width < b.clientX || b.clientY < c.top || c.top + c.height < b.clientY) && Gj.call(this)
             });
             if (!c) {
                 var d = this.L().parentElement || this.L().parentNode;
                 if (d) {
                     var e = this;
                     this.lb = function() {
                         if (a.open) {
                             var b = a.getBoundingClientRect().height,
                                 c = d.getBoundingClientRect().height,
                                 k = d.getBoundingClientRect().top - document.body.getBoundingClientRect().top,
                                 C = d.getBoundingClientRect().left - document.body.getBoundingClientRect().left,
                                 P = a.getBoundingClientRect().width,
                                 oc = d.getBoundingClientRect().width;
                             a.style.top = (k + (c - b) / 2).toString() + "px";
                             b = C + (oc - P) / 2;
                             a.style.left = b.toString() + "px";
                             a.style.right = (document.body.getBoundingClientRect().width - b - P).toString() + "px"
                         } else window.removeEventListener("resize", e.lb)
                     };
                     this.lb();
                     window.addEventListener("resize", this.lb, !1)
                 }
             }
         }
 
         function Gj() {
             var a = Hj.call(this);
             a && (Zi(a), a.open && a.close(), Ec(a), this.lb && window.removeEventListener("resize", this.lb))
         }
 
         function Hj() {
             return yc("firebaseui-id-dialog")
         }
 
         function Ij() {
             Ec(Jj.call(this))
         }
 
         function Jj() {
             return this.o("firebaseui-id-info-bar")
         }
 
         function Kj() {
             return this.o("firebaseui-id-dismiss-info-bar")
         }
         var Lj = {
             Wd: "https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg",
             Vd: "https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/github.svg",
             Nd: "https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/facebook.svg",
             Be: "https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/twitter.svg",
             je: "https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/mail.svg",
             le: "https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/phone.svg"
         };
 
         function Mj(a, b, c) {
             Ke.call(this, a, b);
             for (var d in c) this[d] = c[d]
         }
         r(Mj, Ke);
 
         function O(a, b, c, d) {
             rg.call(this, c);
             this.nd = a;
             this.md = b;
             this.Eb = !1;
             this.dd = d || null;
             this.ea = this.mb = null
         }
         r(O, rg);
         O.prototype.fc = function() {
             var a = sd(this.nd, this.md, Lj, this.Oa());
             Xi(a);
             this.j = a
         };
         O.prototype.m = function() {
             O.h.m.call(this);
             ii(Q(this), new Mj("pageEnter", Q(this), {
                 pageId: this.dd
             }))
         };
         O.prototype.bb = function() {
             ii(Q(this), new Mj("pageExit", Q(this), {
                 pageId: this.dd
             }));
             O.h.bb.call(this)
         };
         O.prototype.f = function() {
             window.clearTimeout(this.mb);
             this.md = this.nd = this.mb = null;
             this.Eb = !1;
             this.ea = null;
             Zi(this.L());
             O.h.f.call(this)
         };
 
         function Nj(a) {
             a.Eb = !0;
             a.mb = window.setTimeout(function() {
                 a.L() && null === a.ea && (a.ea = sd(sh, null, null, a.Oa()), a.L().appendChild(a.ea), Xi(a.ea))
             }, 500)
         }
 
         function Oj(a, b, c, d, e) {
             function f() {
                 if (a.Ma) return null;
                 a.Eb = !1;
                 window.clearTimeout(a.mb);
                 a.mb = null;
                 a.ea && (Zi(a.ea), Ec(a.ea), a.ea = null)
             }
             if (a.Eb) return null;
             Nj(a);
             return b.apply(null, c).then(d, e).then(f, f)
         }
 
         function Q(a) {
             return a.L().parentElement || a.L().parentNode
         }
 
         function Pj(a,
             b, c) {
             zj(a, b, function() {
                 c.focus()
             })
         }
 
         function Qj(a, b, c) {
             zj(a, b, function() {
                 c()
             })
         }
         q(O.prototype, {
             F: function(a) {
                 Ij.call(this);
                 var b = sd(oh, {
                     message: a
                 }, null, this.Oa());
                 this.L().appendChild(b);
                 Cj(this, Kj.call(this), function() {
                     Ec(b)
                 })
             },
             Se: Ij,
             Ve: Jj,
             Ue: Kj,
             Tb: function(a, b) {
                 a = sd(qh, {
                     Cb: a,
                     message: b
                 }, null, this.Oa());
                 Fj.call(this, a)
             },
             ya: Gj,
             Te: Hj
         });
 
         function Rj() {
             return this.o("firebaseui-id-submit")
         }
 
         function Sj() {
             return this.o("firebaseui-id-secondary-link")
         }
 
         function Tj(a, b) {
             var c = Rj.call(this);
             Cj(this, c, function(b) {
                 a(b)
             });
             (c = Sj.call(this)) && b && Cj(this, c, function(a) {
                 b(a)
             })
         }
 
         function Uj() {
             return this.o("firebaseui-id-password")
         }
 
         function Vj() {
             return this.o("firebaseui-id-password-error")
         }
 
         function Wj() {
             var a = Uj.call(this),
                 b = Vj.call(this);
             yj(this, a, function() {
                 Ej(b) && (M(a, !0), Dj(b))
             })
         }
 
         function Xj() {
             var a = Uj.call(this),
                 b;
             b = Vj.call(this);
             G(a) ? (M(a, !0), Dj(b), b = !0) : (M(a, !1), N(b, D("Enter your password").toString()), b = !1);
             return b ? G(a) : null
         }
 
         function Yj(a, b, c, d) {
             O.call(this, Ah, {
                 email: a
             }, d, "passwordLinking");
             this.l = b;
             this.Lb = c
         }
         r(Yj,
             O);
         Yj.prototype.m = function() {
             this.sc();
             this.A(this.l, this.Lb);
             Qj(this, this.aa(), this.l);
             this.aa().focus();
             Yj.h.m.call(this)
         };
         Yj.prototype.f = function() {
             this.l = null;
             Yj.h.f.call(this)
         };
         Yj.prototype.ma = function() {
             return G(this.o("firebaseui-id-email"))
         };
         q(Yj.prototype, {
             aa: Uj,
             mc: Vj,
             sc: Wj,
             dc: Xj,
             D: Rj,
             ba: Sj,
             A: Tj
         });
 
         function Zj() {
             return this.o("firebaseui-id-email")
         }
 
         function ak() {
             return this.o("firebaseui-id-email-error")
         }
 
         function bk(a) {
             var b = Zj.call(this),
                 c = ak.call(this);
             yj(this, b, function() {
                 Ej(c) && (M(b, !0),
                     Dj(c))
             });
             a && zj(this, b, function() {
                 a()
             })
         }
 
         function ck() {
             return sa(G(Zj.call(this)) || "")
         }
 
         function dk() {
             var a = Zj.call(this),
                 b;
             b = ak.call(this);
             var c = G(a) || "";
             c ? Hg.test(c) ? (M(a, !0), Dj(b), b = !0) : (M(a, !1), N(b, D("That email address isn't correct").toString()), b = !1) : (M(a, !1), N(b, D("Enter your email address to continue").toString()), b = !1);
             return b ? sa(G(a)) : null
         }
 
         function ek(a, b, c, d) {
             O.call(this, vh, {
                 email: c
             }, d, "passwordSignIn");
             this.l = a;
             this.Lb = b
         }
         r(ek, O);
         ek.prototype.m = function() {
             this.Da();
             this.sc();
             this.A(this.l,
                 this.Lb);
             Pj(this, this.w(), this.aa());
             Qj(this, this.aa(), this.l);
             G(this.w()) ? this.aa().focus() : this.w().focus();
             ek.h.m.call(this)
         };
         ek.prototype.f = function() {
             this.Lb = this.l = null;
             ek.h.f.call(this)
         };
         q(ek.prototype, {
             w: Zj,
             Pa: ak,
             Da: bk,
             J: ck,
             ma: dk,
             aa: Uj,
             mc: Vj,
             sc: Wj,
             dc: Xj,
             D: Rj,
             ba: Sj,
             A: Tj
         });
 
         function R(a, b, c, d, e) {
             O.call(this, a, b, d, e || "notice");
             this.ha = c || null
         }
         r(R, O);
         R.prototype.m = function() {
             this.ha && (this.A(this.ha), this.D().focus());
             R.h.m.call(this)
         };
         R.prototype.f = function() {
             this.ha = null;
             R.h.f.call(this)
         };
         q(R.prototype, {
             D: Rj,
             ba: Sj,
             A: Tj
         });
 
         function fk(a, b, c) {
             R.call(this, yh, {
                 email: a,
                 N: !!b
             }, b, c, "passwordRecoveryEmailSent")
         }
         r(fk, R);
 
         function gk(a, b) {
             R.call(this, Hh, {
                 N: !!a
             }, a, b, "emailVerificationSuccess")
         }
         r(gk, R);
 
         function hk(a, b) {
             R.call(this, Ih, {
                 N: !!a
             }, a, b, "emailVerificationFailure")
         }
         r(hk, R);
 
         function ik(a, b) {
             R.call(this, Dh, {
                 N: !!a
             }, a, b, "passwordResetSuccess")
         }
         r(ik, R);
 
         function jk(a, b) {
             R.call(this, Eh, {
                 N: !!a
             }, a, b, "passwordResetFailure")
         }
         r(jk, R);
 
         function kk(a, b) {
             R.call(this, Gh, {
                 N: !!a
             }, a, b, "emailChangeRevokeFailure")
         }
         r(kk, R);
 
         function lk(a, b) {
             R.call(this, Jh, {
                 Kd: a
             }, void 0, b, "unrecoverableError")
         }
         r(lk, R);
         var mk = !1,
             nk = null;
 
         function ok(a, b) {
             mk = !!b;
             nk || ("undefined" == typeof accountchooser && Ei() ? (b = gc(), nk = te(qe(Rg(b)), function() {})) : nk = qe());
             nk.then(a, a)
         }
 
         function pk(a, b) {
             a = S(a);
             (a = tj(a).accountChooserInvoked || null) ? a(b): b()
         }
 
         function qk(a, b, c) {
             a = S(a);
             (a = tj(a).accountChooserResult || null) ? a(b, c): c()
         }
 
         function rk(a, b, c, d, e) {
             d ? (L("callback", a, b), mk && c()) : pk(a, function() {
                 bi(function(d) {
                     qk(a, d ? "empty" : "unavailable", function() {
                         L("signIn",
                             a, b);
                         (d || mk) && c()
                     })
                 }, Ui(T(a)), e)
             })
         }
 
         function sk(a, b, c, d) {
             function e(a) {
                 a = U(a);
                 V(b, c, void 0, a);
                 d()
             }
             qk(b, "accountSelected", function() {
                 Ti(!1, T(b));
                 W(b, X(b).fetchProvidersForEmail(a.J()).then(function(e) {
                     tk(b, c, e, a.J(), a.Nc || null || void 0);
                     d()
                 }, e))
             })
         }
 
         function uk(a, b, c, d) {
             qk(b, a ? "addAccount" : "unavailable", function() {
                 L("signIn", b, c);
                 (a || mk) && d()
             })
         }
 
         function vk(a, b, c, d) {
             function e() {
                 var b = a();
                 b && (b = sj(S(b))) && b()
             }
             Zh(function() {
                 var f = a();
                 f && rk(f, b, e, c, d)
             }, function(c) {
                 var d = a();
                 d && sk(c, d, b, e)
             }, function(c) {
                 var d =
                     a();
                 d && uk(c, d, b, e)
             }, a() && ej(S(a())))
         }
 
         function wk(a, b, c, d, e) {
             if (e) xk(a, yk(a).currentUser, c);
             else {
                 if (!c) throw Error("No credential found!");
                 var f = c;
                 c.providerId && "password" == c.providerId && (f = null);
                 var g = function(c) {
                         if (!c.name || "cancel" != c.name) {
                             var d;
                             a: {
                                 var e = c.message;
                                 try {
                                     var f = ((JSON.parse(e).error || {}).message || "").toLowerCase().match(/invalid.+(access|id)_token/);
                                     if (f && f.length) {
                                         d = !0;
                                         break a
                                     }
                                 } catch (g$4) {}
                                 d = !1
                             }
                             d ? (c = Q(b), b.i(), V(a, c, void 0, D("Your sign-in session has expired. Please try again.").toString())) :
                                 (d = c && c.message || "", c.code && (d = U(c)), b.F(d))
                         }
                     },
                     k = X(a).currentUser || d;
                 if (!k) throw Error("User not logged in.");
                 W(a, X(a).signOut().then(function() {
                     var b = new Vh(k.email, k.displayName, k.photoURL, f && f.providerId);
                     null != Pi(Ni, T(a)) && !Pi(Ni, T(a)) || Vi(b, T(a));
                     Qi(Ni, T(a));
                     W(a, yk(a).signInWithCredential(c).then(function(b) {
                         xk(a, b, f)
                     }, g).then(function() {}, g))
                 }, g))
             }
         }
 
         function xk(a, b, c) {
             var d;
             d = S(a);
             d = tj(d).signInSuccess || null;
             var e = T(a),
                 e = Pi(Mi, e) || null || void 0;
             Qi(Mi, T(a));
             var f = !1,
                 g;
             a: {
                 try {
                     g = !!(window.opener &&
                         window.opener.location && window.opener.location.assign && window.opener.location.hostname === window.location.hostname && window.opener.location.protocol === window.location.protocol);
                     break a
                 } catch (k) {}
                 g = !1
             }
             if (g) {
                 if (!d || d(b, c, e)) f = !0, window.opener.location.assign(zk(a, e));
                 d || window.close()
             } else if (!d || d(b, c, e)) f = !0, window.location.assign(zk(a, e));
             f || a.reset()
         }
 
         function zk(a, b) {
             a = b || S(a).g.get("signInSuccessUrl");
             if (!a) throw Error("No redirect URL has been found. You must either specify a signInSuccessUrl in the configuration, pass in a redirect URL to the widget URL, or return false from the callback.");
             return a
         }
 
         function U(a) {
             var b = "";
             switch (a.code) {
                 case "auth/email-already-in-use":
                     b += "The email address is already used by another account";
                     break;
                 case "auth/requires-recent-login":
                     b += Qh();
                     break;
                 case "auth/too-many-requests":
                     b += "You have entered an incorrect password too many times. Please try again in a few minutes.";
                     break;
                 case "auth/user-cancelled":
                     b += "Please authorize the required permissions to sign in to the application";
                     break;
                 case "auth/user-not-found":
                     b += "That email address doesn't match an existing account";
                     break;
                 case "auth/user-token-expired":
                     b += Qh();
                     break;
                 case "auth/weak-password":
                     b += "Strong passwords have at least 6 characters and a mix of letters and numbers";
                     break;
                 case "auth/wrong-password":
                     b += "The email and password you entered don't match";
                     break;
                 case "auth/network-request-failed":
                     b += "A network error has occurred";
                     break;
                 case "auth/invalid-phone-number":
                     b += Oh();
                     break;
                 case "auth/invalid-verification-code":
                     b += D("Wrong code. Try again.");
                     break;
                 case "auth/code-expired":
                     b += "This code is no longer valid";
                     break;
                 case "auth/quota-exceeded":
                     b += "There was a problem verifying your phone number"
             }
             if (b = D(b).toString()) return b;
             try {
                 return JSON.parse(a.message), Ef && Df("Internal error: " + a.message), D("Something went wrong. Please try again.").toString()
             } catch (c) {
                 return a.message
             }
         }
 
         function Ak(a, b, c) {
             var d = mi[b] && firebase.auth[mi[b]] ? new firebase.auth[mi[b]] : null;
             if (!d) throw Error("Invalid Firebase Auth provider!");
             var e;
             e = S(a);
             e = (e = lj(e, b)) && e.scopes;
             e = da(e) ? e : [];
             if (d && d.addScope)
                 for (var f = 0; f < e.length; f++) d.addScope(e[f]);
             a = S(a);
             a = (a = lj(a, b)) && a.customParameters;
             ha(a) ? (a = Za(a), b === firebase.auth.GoogleAuthProvider.PROVIDER_ID && delete a.login_hint) : a = null;
             b == firebase.auth.GoogleAuthProvider.PROVIDER_ID && c && (a = a || {}, a.login_hint = c);
             a && d && d.setCustomParameters && d.setCustomParameters(a);
             return d
         }
 
         function Bk(a, b, c, d) {
             function e() {
                 W(a, Oj(b, p(X(a).signInWithRedirect, X(a)), [k], function() {
                     if ("file:" === (window.location && window.location.protocol)) return W(a, X(a).getRedirectResult().then(function(c) {
                             b.i();
                             L("callback", a, g, qe(c))
                         },
                         f))
                 }, f))
             }
 
             function f(a) {
                 a.name && "cancel" == a.name || (Ef && Df("signInWithRedirect: " + a.code), a = U(a), b.F(a))
             }
             var g = Q(b),
                 k = Ak(a, c, d);
             "redirect" == rj(S(a)) ? e() : W(a, X(a).signInWithPopup(k).then(function(c) {
                 b.i();
                 L("callback", a, g, qe(c))
             }, function(c) {
                 if (!c.name || "cancel" != c.name) switch (c.code) {
                     case "auth/popup-blocked":
                         e();
                         break;
                     case "auth/popup-closed-by-user":
                     case "auth/cancelled-popup-request":
                         break;
                     case "auth/network-request-failed":
                     case "auth/too-many-requests":
                     case "auth/user-cancelled":
                         b.F(U(c));
                         break;
                     default:
                         b.i(), L("callback", a, g, re(c))
                 }
             }))
         }
 
         function Ck(a, b) {
             var c = b.ma(),
                 d = b.dc();
             if (c)
                 if (d) {
                     var e = firebase.auth.EmailAuthProvider.credential(c, d);
                     W(a, Oj(b, p(X(a).signInWithEmailAndPassword, X(a)), [c, d], function() {
                         wk(a, b, e)
                     }, function(a) {
                         if (!a.name || "cancel" != a.name) switch (a.code) {
                             case "auth/email-exists":
                                 M(b.w(), !1);
                                 N(b.Pa(), U(a));
                                 break;
                             case "auth/too-many-requests":
                             case "auth/wrong-password":
                                 M(b.aa(), !1);
                                 N(b.mc(), U(a));
                                 break;
                             default:
                                 Ef && Df("verifyPassword: " + a.message), b.F(U(a))
                         }
                     }))
                 } else b.aa().focus();
             else b.w().focus()
         }
 
         function Dk(a) {
             a = mj(S(a));
             return 1 == a.length && a[0] == firebase.auth.EmailAuthProvider.PROVIDER_ID
         }
 
         function V(a, b, c, d) {
             if (Dk(a)) d ? L("signIn", a, b, c, d) : Ek(a, b, c);
             else {
                 if (c = a) c = mj(S(a)), c = 1 == c.length && c[0] == firebase.auth.PhoneAuthProvider.PROVIDER_ID;
                 c && !d ? L("phoneSignInStart", a, b) : L("providerSignIn", a, b, d)
             }
         }
 
         function Fk(a, b, c, d) {
             var e = Q(b);
             W(a, Oj(b, p(X(a).fetchProvidersForEmail, X(a)), [c], function(f) {
                 Ti(uj(S(a)) == bj, T(a));
                 b.i();
                 tk(a, e, f, c, void 0, d)
             }, function(a) {
                 a = U(a);
                 b.F(a)
             }))
         }
 
         function tk(a,
             b, c, d, e, f) {
             if (c.length)
                 if (La(c, firebase.auth.EmailAuthProvider.PROVIDER_ID)) L("passwordSignIn", a, b, d);
                 else {
                     e = new qi(d);
                     var g = T(a);
                     Ri(Li, e.nb(), g);
                     L("federatedSignIn", a, b, d, c[0], f)
                 }
             else L("passwordSignUp", a, b, d, e)
         }
 
         function Ek(a, b, c) {
             uj(S(a)) == bj ? ok(function() {
                 Xh ? pk(a, function() {
                     bi(function(d) {
                         qk(a, d ? "empty" : "unavailable", function() {
                             L("signIn", a, b, c)
                         })
                     }, Ui(T(a)), hj(S(a)))
                 }) : (Y(a), vk(Gk, b, !1, hj(S(a))))
             }, !1) : (mk = !1, pk(a, function() {
                 qk(a, "unavailable", function() {
                     L("signIn", a, b, c)
                 })
             }))
         }
 
         function Hk(a) {
             var b =
                 window.location.href;
             a = jj(S(a));
             var b = Xc(b, a) || "",
                 c;
             for (c in fj)
                 if (fj[c].toLowerCase() == b.toLowerCase()) return fj[c];
             return "callback"
         }
 
         function Ik(a) {
             var b = window.location.href;
             a = fi(S(a).g, "queryParameterForSignInSuccessUrl");
             return Xc(b, a)
         }
 
         function Jk() {
             return Xc(window.location.href, "oobCode")
         }
 
         function Kk(a, b) {
             var c = di(b);
             switch (Hk(a)) {
                 case "callback":
                     (b = Ik(a)) && Si(b, T(a));
                     L("callback", a, c);
                     break;
                 case "resetPassword":
                     L("passwordReset", a, c, Jk());
                     break;
                 case "recoverEmail":
                     L("emailChangeRevocation",
                         a, c, Jk());
                     break;
                 case "verifyEmail":
                     L("emailVerification", a, c, Jk());
                     break;
                 case "select":
                     if ((b = Ik(a)) && Si(b, T(a)), Xh) {
                         V(a, c);
                         break
                     } else {
                         ok(function() {
                             Y(a);
                             vk(Gk, c, !0)
                         }, !0);
                         return
                     }
                     default:
                         throw Error("Unhandled widget operation.");
             }(b = sj(S(a))) && b()
         }
 
         function Lk(a) {
             O.call(this, zh, void 0, a, "callback")
         }
         r(Lk, O);
 
         function Mk(a, b, c) {
             if (c.user) {
                 var d = Wi(T(a)),
                     e = d && d.J();
                 if (e && !Nk(c.user, e)) Ok(a, b, c.user, c.credential);
                 else {
                     var f = d && d.xa;
                     f ? W(a, c.user.linkWithCredential(f).then(function() {
                         Pk(a, b, f)
                     }, function(c) {
                         Qk(a,
                             b, c)
                     })) : Pk(a, b, c.credential)
                 }
             } else c = Q(b), b.i(), Qi(Li, T(a)), V(a, c)
         }
 
         function Pk(a, b, c) {
             Qi(Li, T(a));
             wk(a, b, c)
         }
 
         function Qk(a, b, c) {
             var d = Q(b);
             Qi(Li, T(a));
             c = U(c);
             b.i();
             V(a, d, void 0, c)
         }
 
         function Rk(a, b, c, d) {
             var e = Q(b);
             W(a, X(a).fetchProvidersForEmail(c).then(function(f) {
                 b.i();
                 f.length ? "password" == f[0] ? L("passwordLinking", a, e, c) : L("federatedLinking", a, e, c, f[0], d) : (Qi(Li, T(a)), L("passwordRecovery", a, e, c, !1, Ph().toString()))
             }, function(c) {
                 Qk(a, b, c)
             }))
         }
 
         function Ok(a, b, c, d) {
             var e = Q(b);
             W(a, X(a).signOut().then(function() {
                 b.i();
                 L("emailMismatch", a, e, c, d)
             }, function(a) {
                 a.name && "cancel" == a.name || (a = U(a.code), b.F(a))
             }))
         }
 
         function Nk(a, b) {
             if (b == a.email) return !0;
             if (a.providerData)
                 for (var c = 0; c < a.providerData.length; c++)
                     if (b == a.providerData[c].email) return !0;
             return !1
         }
         K.callback = function(a, b, c) {
             var d = new Lk;
             d.render(b);
             Z(a, d);
             b = c || a.getRedirectResult();
             W(a, b.then(function(b) {
                 Mk(a, d, b)
             }, function(b) {
                 if (b && "auth/account-exists-with-different-credential" == b.code && b.email && b.credential) {
                     var c = ri(b),
                         g = T(a);
                     Ri(Li, c.nb(), g);
                     Rk(a, d, b.email)
                 } else b &&
                     "auth/user-cancelled" == b.code ? (c = Wi(T(a)), g = U(b), c && c.xa ? Rk(a, d, c.J(), g) : c ? Fk(a, d, c.J(), g) : Qk(a, d, b)) : b && "auth/operation-not-supported-in-this-environment" == b.code && Dk(a) ? Mk(a, d, {
                         user: null,
                         credential: null
                     }) : Qk(a, d, b)
             }))
         };
 
         function Sk(a, b, c, d) {
             O.call(this, Fh, {
                 email: a,
                 N: !!c
             }, d, "emailChangeRevoke");
             this.cd = b;
             this.ha = c || null
         }
         r(Sk, O);
         Sk.prototype.m = function() {
             var a = this;
             Cj(this, this.o("firebaseui-id-reset-password-link"), function() {
                 a.cd()
             });
             this.ha && (this.A(this.ha), this.D().focus());
             Sk.h.m.call(this)
         };
         Sk.prototype.f = function() {
             this.cd = this.ha = null;
             Sk.h.f.call(this)
         };
         q(Sk.prototype, {
             D: Rj,
             ba: Sj,
             A: Tj
         });
 
         function Tk() {
             return this.o("firebaseui-id-new-password")
         }
 
         function Uk() {
             return this.o("firebaseui-id-password-toggle")
         }
 
         function Vk() {
             this.tc = !this.tc;
             var a = Uk.call(this),
                 b = Tk.call(this);
             this.tc ? (b.type = "text", wg(a, "firebaseui-input-toggle-off"), xg(a, "firebaseui-input-toggle-on")) : (b.type = "password", wg(a, "firebaseui-input-toggle-on"), xg(a, "firebaseui-input-toggle-off"));
             b.focus()
         }
 
         function Wk() {
             return this.o("firebaseui-id-new-password-error")
         }
 
         function Xk() {
             this.tc = !1;
             var a = Tk.call(this);
             a.type = "password";
             var b = Wk.call(this);
             yj(this, a, function() {
                 Ej(b) && (M(a, !0), Dj(b))
             });
             var c = Uk.call(this);
             wg(c, "firebaseui-input-toggle-on");
             xg(c, "firebaseui-input-toggle-off");
             Aj(this, a, function() {
                 wg(c, "firebaseui-input-toggle-focus");
                 xg(c, "firebaseui-input-toggle-blur")
             });
             Bj(this, a, function() {
                 wg(c, "firebaseui-input-toggle-blur");
                 xg(c, "firebaseui-input-toggle-focus")
             });
             Cj(this, c, p(Vk, this))
         }
 
         function Yk() {
             var a = Tk.call(this),
                 b;
             b = Wk.call(this);
             G(a) ? (M(a, !0),
                 Dj(b), b = !0) : (M(a, !1), N(b, D("Enter your password").toString()), b = !1);
             return b ? G(a) : null
         }
 
         function Zk(a, b, c) {
             O.call(this, Ch, {
                 email: a
             }, c, "passwordReset");
             this.l = b
         }
         r(Zk, O);
         Zk.prototype.m = function() {
             this.rc();
             this.A(this.l);
             Qj(this, this.U(), this.l);
             this.U().focus();
             Zk.h.m.call(this)
         };
         Zk.prototype.f = function() {
             this.l = null;
             Zk.h.f.call(this)
         };
         q(Zk.prototype, {
             U: Tk,
             lc: Wk,
             Qd: Uk,
             rc: Xk,
             cc: Yk,
             D: Rj,
             ba: Sj,
             A: Tj
         });
 
         function $k(a, b, c, d) {
             var e = c.cc();
             e && W(a, Oj(c, p(X(a).confirmPasswordReset, X(a)), [d, e], function() {
                 c.i();
                 var d = new ik;
                 d.render(b);
                 Z(a, d)
             }, function(d) {
                 al(a, b, c, d)
             }))
         }
 
         function al(a, b, c, d) {
             "auth/weak-password" == (d && d.code) ? (a = U(d), M(c.U(), !1), N(c.lc(), a), c.U().focus()) : (c && c.i(), c = new jk, c.render(b), Z(a, c))
         }
 
         function bl(a, b, c) {
             var d = new Sk(c, function() {
                 W(a, Oj(d, p(X(a).sendPasswordResetEmail, X(a)), [c], function() {
                     d.i();
                     d = new fk(c);
                     d.render(b);
                     Z(a, d)
                 }, function() {
                     d.F(D("Unable to send password reset code to specified email").toString())
                 }))
             });
             d.render(b);
             Z(a, d)
         }
         K.passwordReset = function(a, b, c) {
             W(a, X(a).verifyPasswordResetCode(c).then(function(d) {
                 var e =
                     new Zk(d, function() {
                         $k(a, b, e, c)
                     });
                 e.render(b);
                 Z(a, e)
             }, function() {
                 al(a, b)
             }))
         };
         K.emailChangeRevocation = function(a, b, c) {
             var d = null;
             W(a, X(a).checkActionCode(c).then(function(b) {
                 d = b.data.email;
                 return X(a).applyActionCode(c)
             }).then(function() {
                 bl(a, b, d)
             }, function() {
                 var c = new kk;
                 c.render(b);
                 Z(a, c)
             }))
         };
         K.emailVerification = function(a, b, c) {
             W(a, X(a).applyActionCode(c).then(function() {
                 var c = new gk;
                 c.render(b);
                 Z(a, c)
             }, function() {
                 var c = new hk;
                 c.render(b);
                 Z(a, c)
             }))
         };
 
         function cl(a, b, c, d, e) {
             O.call(this, Kh, {
                     Ee: a,
                     ke: b
                 },
                 e, "emailMismatch");
             this.ha = c;
             this.I = d
         }
         r(cl, O);
         cl.prototype.m = function() {
             this.A(this.ha, this.I);
             this.D().focus();
             cl.h.m.call(this)
         };
         cl.prototype.f = function() {
             this.I = this.l = null;
             cl.h.f.call(this)
         };
         q(cl.prototype, {
             D: Rj,
             ba: Sj,
             A: Tj
         });
         K.emailMismatch = function(a, b, c, d) {
             var e = Wi(T(a));
             if (e) {
                 var f = new cl(c.email, e.J(), function() {
                     var b = f;
                     Qi(Li, T(a));
                     wk(a, b, d, c)
                 }, function() {
                     var b = d.providerId,
                         c = Q(f);
                     f.i();
                     e.xa ? L("federatedLinking", a, c, e.J(), b) : L("federatedSignIn", a, c, e.J(), b)
                 });
                 f.render(b);
                 Z(a, f)
             } else V(a, b)
         };
 
         function dl(a, b, c, d) {
             O.call(this, Bh, {
                 email: a,
                 providerId: b
             }, d, "federatedLinking");
             this.l = c
         }
         r(dl, O);
         dl.prototype.m = function() {
             this.A(this.l);
             this.D().focus();
             dl.h.m.call(this)
         };
         dl.prototype.f = function() {
             this.l = null;
             dl.h.f.call(this)
         };
         q(dl.prototype, {
             D: Rj,
             A: Tj
         });
         K.federatedLinking = function(a, b, c, d, e) {
             var f = Wi(T(a));
             if (f && f.xa) {
                 var g = new dl(c, d, function() {
                     Bk(a, g, d, c)
                 });
                 g.render(b);
                 Z(a, g);
                 e && g.F(e)
             } else V(a, b)
         };
         K.federatedSignIn = function(a, b, c, d, e) {
             var f = new dl(c, d, function() {
                 Bk(a, f, d, c)
             });
             f.render(b);
             Z(a, f);
             e && f.F(e)
         };
 
         function el(a, b, c, d) {
             var e = b.dc();
             e ? W(a, Oj(b, p(X(a).signInWithEmailAndPassword, X(a)), [c, e], function(c) {
                 return W(a, c.linkWithCredential(d).then(function() {
                     wk(a, b, d)
                 }))
             }, function(a) {
                 if (!a.name || "cancel" != a.name) switch (a.code) {
                     case "auth/wrong-password":
                         M(b.aa(), !1);
                         N(b.mc(), U(a));
                         break;
                     case "auth/too-many-requests":
                         b.F(U(a));
                         break;
                     default:
                         Ef && Df("signInWithEmailAndPassword: " + a.message), b.F(U(a))
                 }
             })) : b.aa().focus()
         }
         K.passwordLinking = function(a, b, c) {
             var d = Wi(T(a));
             Qi(Li, T(a));
             var e =
                 d && d.xa;
             if (e) {
                 var f = new Yj(c, function() {
                     el(a, f, c, e)
                 }, function() {
                     f.i();
                     L("passwordRecovery", a, b, c)
                 });
                 f.render(b);
                 Z(a, f)
             } else V(a, b)
         };
 
         function fl(a, b, c, d) {
             O.call(this, xh, {
                 email: c,
                 Yb: !!b
             }, d, "passwordRecovery");
             this.l = a;
             this.I = b
         }
         r(fl, O);
         fl.prototype.m = function() {
             this.Da();
             this.A(this.l, this.I);
             G(this.w()) || this.w().focus();
             Qj(this, this.w(), this.l);
             fl.h.m.call(this)
         };
         fl.prototype.f = function() {
             this.I = this.l = null;
             fl.h.f.call(this)
         };
         q(fl.prototype, {
             w: Zj,
             Pa: ak,
             Da: bk,
             J: ck,
             ma: dk,
             D: Rj,
             ba: Sj,
             A: Tj
         });
 
         function gl(a,
             b) {
             var c = b.ma();
             if (c) {
                 var d = Q(b);
                 W(a, Oj(b, p(X(a).sendPasswordResetEmail, X(a)), [c], function() {
                     b.i();
                     var e = new fk(c, function() {
                         e.i();
                         V(a, d)
                     });
                     e.render(d);
                     Z(a, e)
                 }, function(a) {
                     M(b.w(), !1);
                     N(b.Pa(), U(a))
                 }))
             } else b.w().focus()
         }
         K.passwordRecovery = function(a, b, c, d, e) {
             var f = new fl(function() {
                 gl(a, f)
             }, d ? void 0 : function() {
                 f.i();
                 V(a, b)
             }, c);
             f.render(b);
             Z(a, f);
             e && f.F(e)
         };
         K.passwordSignIn = function(a, b, c) {
             var d = new ek(function() {
                 Ck(a, d)
             }, function() {
                 var c = d.J();
                 d.i();
                 L("passwordRecovery", a, b, c)
             }, c);
             d.render(b);
             Z(a,
                 d)
         };
 
         function hl() {
             return this.o("firebaseui-id-name")
         }
 
         function il() {
             return this.o("firebaseui-id-name-error")
         }
 
         function jl(a, b, c, d, e, f, g) {
             O.call(this, wh, {
                 email: e,
                 pe: b,
                 name: f,
                 ob: a,
                 Yb: !!d
             }, g, "passwordSignUp");
             this.l = c;
             this.I = d;
             this.yc = b
         }
         r(jl, O);
         jl.prototype.m = function() {
             this.Da();
             this.yc && this.ae();
             this.rc();
             this.A(this.l, this.I);
             this.Ia();
             jl.h.m.call(this)
         };
         jl.prototype.f = function() {
             this.I = this.l = null;
             jl.h.f.call(this)
         };
         jl.prototype.Ia = function() {
             this.yc ? (Pj(this, this.w(), this.eb()), Pj(this, this.eb(),
                 this.U())) : Pj(this, this.w(), this.U());
             this.l && Qj(this, this.U(), this.l);
             G(this.w()) ? this.yc && !G(this.eb()) ? this.eb().focus() : this.U().focus() : this.w().focus()
         };
         q(jl.prototype, {
             w: Zj,
             Pa: ak,
             Da: bk,
             J: ck,
             ma: dk,
             eb: hl,
             We: il,
             ae: function() {
                 var a = hl.call(this),
                     b = il.call(this);
                 yj(this, a, function() {
                     Ej(b) && (M(a, !0), Dj(b))
                 })
             },
             Bd: function() {
                 var a = hl.call(this),
                     b;
                 b = il.call(this);
                 var c = G(a),
                     c = !/^[\s\xa0]*$/.test(null == c ? "" : String(c));
                 M(a, c);
                 c ? (Dj(b), b = !0) : (N(b, D("Enter your account name").toString()), b = !1);
                 return b ?
                     sa(G(a)) : null
             },
             U: Tk,
             lc: Wk,
             Qd: Uk,
             rc: Xk,
             cc: Yk,
             D: Rj,
             ba: Sj,
             A: Tj
         });
 
         function kl(a, b) {
             var c = qj(S(a)),
                 d = b.ma(),
                 e = null;
             c && (e = b.Bd());
             var f = b.cc();
             if (d)
                 if (c && !e) b.eb().focus();
                 else if (f) {
                 var g = firebase.auth.EmailAuthProvider.credential(d, f);
                 W(a, Oj(b, p(X(a).createUserWithEmailAndPassword, X(a)), [d, f], function(d) {
                     return c ? W(a, d.updateProfile({
                         displayName: e
                     }).then(function() {
                         wk(a, b, g)
                     })) : wk(a, b, g)
                 }, function(c) {
                     if (!c.name || "cancel" != c.name) {
                         var e = U(c);
                         switch (c.code) {
                             case "auth/email-already-in-use":
                                 return ll(a,
                                     b, d, c);
                             case "auth/too-many-requests":
                                 e = D("Too many account requests are coming from your IP address. Try again in a few minutes.").toString();
                             case "auth/operation-not-allowed":
                             case "auth/weak-password":
                                 M(b.U(), !1);
                                 N(b.lc(), e);
                                 break;
                             default:
                                 c = "setAccountInfo: " + nf(c), Ef && Df(c), b.F(e)
                         }
                     }
                 }))
             } else b.U().focus();
             else b.w().focus()
         }
 
         function ll(a, b, c, d) {
             function e() {
                 var a = U(d);
                 M(b.w(), !1);
                 N(b.Pa(), a);
                 b.w().focus()
             }
             var f = X(a).fetchProvidersForEmail(c).then(function(d) {
                 d.length ? e() : (d = Q(b), b.i(), L("passwordRecovery",
                     a, d, c, !1, Ph().toString()))
             }, function() {
                 e()
             });
             W(a, f);
             return f
         }
         K.passwordSignUp = function(a, b, c, d, e) {
             function f() {
                 g.i();
                 V(a, b)
             }
             var g = new jl(pj(S(a)), qj(S(a)), function() {
                 kl(a, g)
             }, e ? void 0 : f, c, d);
             g.render(b);
             Z(a, g)
         };
 
         function ml() {
             return this.o("firebaseui-id-phone-confirmation-code")
         }
 
         function nl() {
             return this.o("firebaseui-id-phone-confirmation-code-error")
         }
 
         function ol() {
             return this.o("firebaseui-id-resend-countdown")
         }
 
         function pl(a, b, c, d, e, f, g, k) {
             O.call(this, Nh, {
                 phoneNumber: e,
                 ob: g
             }, k, "phoneSignInFinish");
             this.qe = f;
             this.Ha = new kf(1E3);
             this.zc = f;
             this.$c = a;
             this.l = b;
             this.I = c;
             this.bd = d
         }
         r(pl, O);
         pl.prototype.m = function() {
             var a = this;
             this.qd(this.qe);
             We(this.Ha, "tick", this.pc, !1, this);
             this.Ha.start();
             Cj(this, this.o("firebaseui-id-change-phone-number-link"), function() {
                 a.$c()
             });
             Cj(this, this.Rc(), function() {
                 a.bd()
             });
             this.be(this.l);
             this.A(this.l, this.I);
             this.Ia();
             pl.h.m.call(this)
         };
         pl.prototype.f = function() {
             this.bd = this.I = this.l = this.$c = null;
             this.Ha.stop();
             df(this.Ha, "tick", this.pc);
             this.Ha = null;
             pl.h.f.call(this)
         };
         pl.prototype.pc = function() {
             --this.zc;
             0 < this.zc ? this.qd(this.zc) : (this.Ha.stop(), df(this.Ha, "tick", this.pc), this.Yd(), this.ue())
         };
         pl.prototype.Ia = function() {
             this.nc().focus()
         };
         q(pl.prototype, {
             nc: ml,
             Rd: nl,
             be: function(a) {
                 var b = ml.call(this),
                     c = nl.call(this);
                 yj(this, b, function() {
                     Ej(c) && (M(b, !0), Dj(c))
                 });
                 a && zj(this, b, function() {
                     a()
                 })
             },
             Cd: function() {
                 var a = sa(G(ml.call(this)) || "");
                 return /^\d{6}$/.test(a) ? a : null
             },
             Ud: ol,
             qd: function(a) {
                 var b = ol.call(this);
                 Fc(b, D("Resend code in " + ((9 < a ? "0:" : "0:0") + a)).toString())
             },
             Yd: function() {
                 var a = this.Ud();
                 Dj(a)
             },
             Rc: function() {
                 return this.o("firebaseui-id-resend-link")
             },
             ue: function() {
                 var a = this.Rc();
                 N(a)
             },
             D: Rj,
             ba: Sj,
             A: Tj
         });
 
         function ql(a, b, c, d) {
             function e(a) {
                 b.nc().focus();
                 M(b.nc(), !1);
                 N(b.Rd(), a)
             }
             var f = b.Cd();
             f ? (b.Tb("mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active firebaseui-progress-dialog-loading-icon", D("Verifying...").toString()), W(a, Oj(b, p(d.confirm, d), [f], function() {
                 b.ya();
                 b.Tb("firebaseui-icon-done", D("Verified!").toString());
                 var c = setTimeout(function() {
                     b.ya();
                     b.i();
                     wk(a, b, null, null, !0)
                 }, 1E3);
                 W(a, function() {
                     b && b.ya();
                     clearTimeout(c)
                 })
             }, function(d) {
                 b.ya();
                 if (!d.name || "cancel" != d.name) {
                     var f = U(d);
                     switch (d.code) {
                         case "auth/code-expired":
                             d = Q(b);
                             b.i();
                             L("phoneSignInStart", a, d, c, f);
                             break;
                         case "auth/missing-verification-code":
                         case "auth/invalid-verification-code":
                             e(f);
                             break;
                         default:
                             b.F(f)
                     }
                 }
             }))) : e(D("Wrong code. Try again.").toString())
         }
         K.phoneSignInFinish = function(a, b, c, d, e, f) {
             var g = new pl(function() {
                     g.i();
                     L("phoneSignInStart", a, b, c)
                 }, function() {
                     ql(a, g, c, e)
                 },
                 function() {
                     g.i();
                     V(a, b)
                 },
                 function() {
                     g.i();
                     L("phoneSignInStart", a, b, c)
                 }, ti(c), d, pj(S(a)));
             g.render(b);
             Z(a, g);
             f && g.F(f)
         };
 
         function rl(a, b, c) {
             a = sd(rh, {
                 items: a
             }, null, this.Oa());
             Fj.call(this, a, !0, !0);
             c && (c = sl(a, c)) && (c.focus(), $f(c, a));
             Cj(this, a, function(a) {
                 if (a = (a = Gc(a.target, "firebaseui-id-list-box-dialog-button")) && Fg(a, "listboxid")) Gj(), b(a)
             })
         }
 
         function sl(a, b) {
             a = (a || document).getElementsByTagName(String(Ab));
             for (var c = 0; c < a.length; c++)
                 if (Fg(a[c], "listboxid") === b) return a[c];
             return null
         }
 
         function tl() {
             return this.o("firebaseui-id-phone-number")
         }
 
         function ul() {
             return this.o("firebaseui-id-phone-number-error")
         }
 
         function vl() {
             return Ia(Th, function(a) {
                 return {
                     id: a.b,
                     Cb: "firebaseui-flag " + wl(a),
                     label: a.name + " " + ("\u200e+" + a.a)
                 }
             })
         }
 
         function wl(a) {
             return "firebaseui-flag-" + a.c
         }
 
         function xl() {
             var a = this;
             rl.call(this, vl(), function(b) {
                 yl.call(a, b, !0);
                 a.Aa().focus()
             }, this.kb)
         }
 
         function yl(a, b) {
             var c = Sh(a);
             if (c) {
                 if (b) {
                     var d = sa(G(tl.call(this)) || "");
                     b = Uh.search(d);
                     if (b.length && b[0].a != c.a) {
                         d = "+" + c.a + d.substr(b[0].a.length + 1);
                         b = tl.call(this);
                         var e = b.type;
                         if (m(e)) switch (e.toLowerCase()) {
                             case "checkbox":
                             case "radio":
                                 b.checked =
                                     d;
                                 break;
                             case "select-one":
                                 b.selectedIndex = -1;
                                 if (n(d))
                                     for (var f = 0; e = b.options[f]; f++)
                                         if (e.value == d) {
                                             e.selected = !0;
                                             break
                                         } break;
                             case "select-multiple":
                                 n(d) && (d = [d]);
                                 for (f = 0; e = b.options[f]; f++)
                                     if (e.selected = !1, d)
                                         for (var g, k = 0; g = d[k]; k++) e.value == g && (e.selected = !0);
                                 break;
                             default:
                                 b.value = null != d ? d : ""
                         }
                     }
                 }
                 b = Sh(this.kb);
                 this.kb = a;
                 a = this.o("firebaseui-id-country-selector-flag");
                 b && xg(a, wl(b));
                 wg(a, wl(c));
                 c = "\u200e+" + c.a;
                 Fc(this.o("firebaseui-id-country-selector-code"), c)
             }
         }
 
         function zl(a, b, c, d, e, f) {
             O.call(this,
                 Mh, {
                     Id: c,
                     Jb: e || null
                 }, f, "phoneSignInStart");
             this.Ed = d || null;
             this.Jd = c;
             this.l = a;
             this.I = b
         }
         r(zl, O);
         zl.prototype.m = function() {
             this.ce(this.Ed);
             this.A(this.l, this.I);
             this.Ia();
             zl.h.m.call(this)
         };
         zl.prototype.f = function() {
             this.I = this.l = null;
             zl.h.f.call(this)
         };
         zl.prototype.Ia = function() {
             this.Jd || Pj(this, this.Aa(), this.D());
             Qj(this, this.D(), this.l);
             this.Aa().focus();
             Gg(this.Aa(), (this.Aa().value || "").length)
         };
         q(zl.prototype, {
             Aa: tl,
             Qc: ul,
             ce: function(a, b) {
                 var c = this,
                     d = tl.call(this),
                     e = this.o("firebaseui-id-country-selector"),
                     f = ul.call(this);
                 yl.call(this, a || "1-US-0");
                 Cj(this, e, function() {
                     xl.call(c)
                 });
                 yj(this, d, function() {
                     Ej(f) && (M(d, !0), Dj(f));
                     var a = sa(G(d) || ""),
                         b = Sh(this.kb),
                         a = Uh.search(a);
                     a.length && a[0].a != b.a && (b = a[0], yl.call(c, "1" == b.a ? "1-US-0" : b.b))
                 });
                 b && zj(this, d, function() {
                     b()
                 })
             },
             Sd: function() {
                 var a = sa(G(tl.call(this)) || ""),
                     b = Uh.search(a),
                     c = Sh(this.kb);
                 b.length && b[0].a != c.a && yl.call(this, b[0].b);
                 b.length && (a = a.substr(b[0].a.length + 1));
                 return a ? new si(this.kb, a) : null
             },
             Td: function() {
                 return this.o("firebaseui-recaptcha-container")
             },
             oc: function() {
                 return this.o("firebaseui-id-recaptcha-error")
             },
             D: Rj,
             ba: Sj,
             A: Tj
         });
 
         function Al(a, b, c, d) {
             var e = b.Sd();
             e ? vj ? (b.Tb("mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active firebaseui-progress-dialog-loading-icon", D("Verifying...").toString()), W(a, Oj(b, p(yk(a).signInWithPhoneNumber, yk(a)), [ti(e), c], function(c) {
                 var d = Q(b);
                 b.Tb("firebaseui-icon-done", D("Code sent!").toString());
                 var k = setTimeout(function() {
                     b.ya();
                     b.i();
                     L("phoneSignInFinish", a, d, e, 15, c)
                 }, 1E3);
                 W(a, function() {
                     b && b.ya();
                     clearTimeout(k)
                 })
             }, function(a) {
                 b.ya();
                 if (!a.name || "cancel" != a.name) {
                     grecaptcha.reset(xj);
                     vj = null;
                     var c = a && a.message || "";
                     if (a.code) switch (a.code) {
                         case "auth/too-many-requests":
                             c = D("This phone number has been used too many times").toString();
                             break;
                         case "auth/invalid-phone-number":
                         case "auth/missing-phone-number":
                             b.Aa().focus();
                             N(b.Qc(), Oh().toString());
                             return;
                         default:
                             c = U(a)
                     }
                     b.F(c)
                 }
             }))) : wj ? N(b.oc(), D("Solve the reCAPTCHA").toString()) : !wj && d && b.D().click() : (b.Aa().focus(), N(b.Qc(), Oh().toString()))
         }
         K.phoneSignInStart = function(a, b, c, d) {
             var e = nj(S(a)) || {};
             vj = null;
             wj = !(e && "invisible" === e.size);
             var f = oj(S(a)),
                 g = new zl(function(b) {
                     Al(a, g, k, !(!b || !b.keyCode))
                 }, function() {
                     k.clear();
                     g.i();
                     V(a, b)
                 }, wj, c && c.ec || f && f.b || null, c && c.Jb);
             g.render(b);
             Z(a, g);
             d && g.F(d);
             e.callback = function(b) {
                 g.oc() && Dj(g.oc());
                 vj = b;
                 wj || Al(a, g, k)
             };
             e["expired-callback"] = function() {
                 vj = null
             };
             var k = new firebase.auth.RecaptchaVerifier(wj ? g.Td() : g.D(), e, yk(a).app);
             W(a, Oj(g, p(k.render, k), [], function(a) {
                 xj = a
             }, function(c) {
                 c.name && "cancel" ==
                     c.name || (c = U(c), g.i(), V(a, b, void 0, c))
             }))
         };
 
         function Bl(a, b, c) {
             O.call(this, Lh, {
                 ne: b
             }, c, "providerSignIn");
             this.ad = a
         }
         r(Bl, O);
         Bl.prototype.m = function() {
             this.$d(this.ad);
             Bl.h.m.call(this)
         };
         Bl.prototype.f = function() {
             this.ad = null;
             Bl.h.f.call(this)
         };
         q(Bl.prototype, {
             $d: function(a) {
                 function b(b) {
                     a(b)
                 }
                 for (var c = this.kc("firebaseui-id-idp-button"), d = 0; d < c.length; d++) {
                     var e = c[d],
                         f = Fg(e, "providerId");
                     Cj(this, e, ma(b, f))
                 }
             }
         });
         K.providerSignIn = function(a, b, c) {
             var d = new Bl(function(c) {
                 c == firebase.auth.EmailAuthProvider.PROVIDER_ID ?
                     (d.i(), Ek(a, b)) : c == firebase.auth.PhoneAuthProvider.PROVIDER_ID ? (d.i(), L("phoneSignInStart", a, b)) : Bk(a, d, c)
             }, mj(S(a)));
             d.render(b);
             Z(a, d);
             c && d.F(c)
         };
 
         function Cl(a, b, c, d) {
             O.call(this, uh, {
                 email: c,
                 Gd: !!b
             }, d, "signIn");
             this.wc = a;
             this.I = b
         }
         r(Cl, O);
         Cl.prototype.m = function() {
             this.Da(this.wc);
             this.A(this.wc, this.I || void 0);
             this.Ia();
             Cl.h.m.call(this)
         };
         Cl.prototype.f = function() {
             this.I = this.wc = null;
             Cl.h.f.call(this)
         };
         Cl.prototype.Ia = function() {
             this.w().focus();
             Gg(this.w(), (this.w().value || "").length)
         };
         q(Cl.prototype, {
             w: Zj,
             Pa: ak,
             Da: bk,
             J: ck,
             ma: dk,
             D: Rj,
             ba: Sj,
             A: Tj
         });
         K.signIn = function(a, b, c, d) {
             var e = Dk(a) && uj(S(a)) != bj,
                 f = new Cl(function() {
                     var b = f,
                         c = b.ma() || "";
                     c && Fk(a, b, c)
                 }, e ? null : function() {
                     f.i();
                     V(a, b, c)
                 }, c);
             f.render(b);
             Z(a, f);
             d && f.F(d)
         };
 
         function Dl(a, b) {
             this.Kc = !1;
             var c = El(b);
             if (Fl[c]) throw Error('An AuthUI instance already exists for the key "' + c + '"');
             Fl[c] = this;
             this.zd = a;
             this.ld = firebase.initializeApp({
                 apiKey: a.app.options.apiKey,
                 authDomain: a.app.options.authDomain
             }, a.app.name + "-firebaseui-temp").auth();
             this.xd =
                 b;
             this.g = new aj;
             this.tb = this.Wb = this.wb = this.Fc = null;
             this.sa = []
         }
         var Fl = {};
 
         function El(a) {
             return a || "[DEFAULT]"
         }
         Dl.prototype.getRedirectResult = function() {
             Y(this);
             this.wb || (this.wb = qe(X(this).getRedirectResult()));
             return this.wb
         };
 
         function Z(a, b) {
             Y(a);
             a.tb = b
         }
         var Gl = null;
 
         function Gk() {
             return Gl
         }
 
         function X(a) {
             Y(a);
             return a.ld
         }
 
         function yk(a) {
             Y(a);
             return a.zd
         }
 
         function T(a) {
             Y(a);
             return a.xd
         }
         h = Dl.prototype;
         h.start = function(a, b) {
             Y(this);
             var c = this;
             this.Sb(b);
             "complete" == l.document.readyState ? Hl(this, a) : cf(window,
                 "load",
                 function() {
                     Hl(c, a)
                 })
         };
 
         function Hl(a, b) {
             var c = di(b),
                 d = "en".replace(/_/g, "-");
             c.setAttribute("lang", d);
             Gl && (d = Gl, Y(d), Wi(T(d)) && Ef && Ef.log(zf, "UI Widget is already rendered on the page and is pending some user interaction. Only one widget instance can be rendered per page. The previous instance has been automatically reset.", void 0), Gl.reset());
             Gl = a;
             a.Wb = c;
             Il(a, c);
             bh(new ch) && bh(new dh) ? Kk(a, b) : (b = di(b), c = new lk(D("The browser you are using does not support Web Storage. Please try again in a different browser.").toString()),
                 c.render(b), Z(a, c))
         }
 
         function W(a, b) {
             Y(a);
             if (b) {
                 a.sa.push(b);
                 var c = function() {
                     Pa(a.sa, function(a) {
                         return a == b
                     })
                 };
                 "function" != typeof b && b.then(c, c)
             }
         }
         h.reset = function() {
             Y(this);
             this.Wb && this.Wb.removeAttribute("lang");
             this.wb = qe({
                 user: null,
                 credential: null
             });
             Gl == this && (Gl = null);
             this.Wb = null;
             for (var a = 0; a < this.sa.length; a++)
                 if ("function" == typeof this.sa[a]) this.sa[a]();
                 else this.sa[a].cancel && this.sa[a].cancel();
             this.sa = [];
             Qi(Li, T(this));
             this.tb && (this.tb.i(), this.tb = null);
             this.ub = null
         };
 
         function Il(a,
             b) {
             a.ub = null;
             a.Fc = new li(b);
             a.Fc.register();
             We(a.Fc, "pageEnter", function(b) {
                 b = b && b.Xe;
                 if (a.ub != b) {
                     var d;
                     d = S(a);
                     (d = tj(d).uiChanged || null) && d(a.ub, b);
                     a.ub = b
                 }
             })
         }
         h.Sb = function(a) {
             Y(this);
             this.g.Sb(a)
         };
 
         function S(a) {
             Y(a);
             return a.g
         }
         h.we = function() {
             Y(this);
             var a, b = S(this),
                 c = fi(b.g, "widgetUrl");
             a = ij(b, c);
             if (S(this).g.get("popupMode")) {
                 var b = (window.screen.availHeight - 600) / 2,
                     c = (window.screen.availWidth - 500) / 2,
                     d = a || "about:blank",
                     b = {
                         width: 500,
                         height: 600,
                         top: 0 < b ? b : 0,
                         left: 0 < c ? c : 0,
                         location: !0,
                         resizable: !0,
                         statusbar: !0,
                         toolbar: !1
                     };
                 b.target = b.target || d.target || "google_popup";
                 b.width = b.width || 690;
                 b.height = b.height || 500;
                 var e;
                 (c = b) || (c = {});
                 b = window;
                 a = d instanceof hc ? d : lc("undefined" != typeof d.href ? d.href : String(d));
                 var d = c.target || d.target,
                     f = [];
                 for (e in c) switch (e) {
                     case "width":
                     case "height":
                     case "top":
                     case "left":
                         f.push(e + "=" + c[e]);
                         break;
                     case "target":
                     case "noreferrer":
                         break;
                     default:
                         f.push(e + "=" + (c[e] ? 1 : 0))
                 }
                 e = f.join(",");
                 (t("iPhone") && !t("iPod") && !t("iPad") || t("iPad") || t("iPod")) && b.navigator && b.navigator.standalone &&
                     d && "_self" != d ? (e = b.document.createElement(String(vb)), a = a instanceof hc ? a : lc(a), e.href = jc(a), e.setAttribute("target", d), c.noreferrer && e.setAttribute("rel", "noreferrer"), c = document.createEvent("MouseEvent"), c.initMouseEvent("click", !0, !0, b, 1), e.dispatchEvent(c), e = {}) : c.noreferrer ? (e = b.open("", d, e), b = jc(a), e && (hb && -1 != b.indexOf(";") && (b = "'" + b.replace(/'/g, "%27") + "'"), e.opener = null, dc("b/12014412, meta tag with sanitized URL"), b = '<META HTTP-EQUIV="refresh" content="0; url=' + ta(b) + '">', e.document.write(qc((new nc).de(b))),
                         e.document.close())) : e = b.open(jc(a), d, e);
                 e && e.focus()
             } else window.location.assign(a)
         };
 
         function Y(a) {
             if (a.Kc) throw Error("AuthUI instance is deleted!");
         }
         h.delete = function() {
             var a = this;
             Y(this);
             return this.ld.app.delete().then(function() {
                 var b = El(T(a));
                 delete Fl[b];
                 a.reset();
                 a.Kc = !0
             })
         };
         oa("firebaseui.auth.AuthUI", Dl);
         oa("firebaseui.auth.AuthUI.getInstance", function(a) {
             a = El(a);
             return Fl[a] ? Fl[a] : null
         });
         oa("firebaseui.auth.AuthUI.prototype.start", Dl.prototype.start);
         oa("firebaseui.auth.AuthUI.prototype.setConfig",
             Dl.prototype.Sb);
         oa("firebaseui.auth.AuthUI.prototype.signIn", Dl.prototype.we);
         oa("firebaseui.auth.AuthUI.prototype.reset", Dl.prototype.reset);
         oa("firebaseui.auth.AuthUI.prototype.delete", Dl.prototype.delete);
         oa("firebaseui.auth.CredentialHelper.ACCOUNT_CHOOSER_COM", bj);
         oa("firebaseui.auth.CredentialHelper.NONE", "none")
     })();
 })();