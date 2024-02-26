/*
 * @autor: MultiFour
 * @version: 1.0.0
 */

"use strict";

var Modal = function(id, type, targetObject) {
    var _this = this;


    var footer = document.getElementById('modal-container');
    var modal = this._modal(id, type);
    this._selfDOM = modal;
    footer.appendChild(modal);

    this._targetObject = targetObject;

    this._title.innerHTML = '';
    this._body.innerHTML = '';
    this._footer.innerHTML = '';

    this['_getModal' + type](_this);

    $(modal).on('hidden.bs.modal', function() {
        modal.parentElement.removeChild(modal);
    });

    return modal;
};

Modal.prototype = {
    _selfDOM: null

    , _header: null
    , _title: null
    , _body: null
    , _footer: null

    , _elements: null
    , _elementsGallery: null

    , _countDropDown: 0

    , _targetObject: null

    /**
     * Creatig modal dialog
     * @param id
     * @returns {Element}
     * @private
     */
    , _modal: function(id, type) {
        var classModal = type === 'ButtonSettings' ? builder.defaultStyleType + '-modal' : '';
        var modal = document.createElement('div');
        modal.className = 'modal fade flex-center ' + classModal;
        modal.id = id;
        modal.setAttribute('tabindex', '-1');
        modal.setAttribute('role', 'dialog');

        var content = '<div class="modal-dialog" role="document">'
            + '<div class="modal-content">'
            + '<div class="modal-header">'
            + '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="icon-cross"></i></button>'
            + '<div class="modal-title">Modal title</div>'
            + '</div>'
            + '<div class="modal-body clearfix">'
            + '</div>'
            + '<div class="modal-footer">'
            + '<button type="button" class="supra-btn btn-default" data-dismiss="modal">Cancel</button>'
            + '</div>'
            + '</div>';

        modal.innerHTML = content;

        this._header = modal.querySelector('.modal-header');
        this._title = this._header.querySelector('.modal-title');
        this._body = modal.querySelector('.modal-body');
        this._footer = modal.querySelector('.modal-footer');

        return modal;
    }
    /**
     * Constructor for elements of modal
     * @param arrElements
     * @param classSide
     * @private
     */
    , _constructModalBody: function(arrElements, classSide) {
        var elements = this._getElements(arrElements);

        if (this._elements) {
            for (var attrname in elements) {
                this._elements[attrname] = elements[attrname];
            }
        } else {
            this._elements = elements;
        }

        var side = document.createElement('div');
        side.className = classSide;

        for (var element in elements) {
            side.appendChild(elements[element]);
        }

        this._body.appendChild(side);
    }
    /**
     * Create elements for modal from arrElements
     * @param arrElements {Array} function names
     * @returns {Array} elements for modals
     * @private
     */
    , _getElements: function(arrElements) {
        var _this = this;
        var arr = {};

        arrElements.forEach(function(element) {
            if (typeof element === 'string') {
                arr[element] = _this['_' + element]();
            } else {
                arr[element.name] = _this['_' + element.func](element.args);
            }
        });

        return arr;
    }
    /**
     * -------------------------------------------- Part - create modal elements ------------------------------------
     */
    /**
     * 
     * @param {type} args
     * @returns {HTMLElement}
     * @private
     */
    , _choiceElement: function(args) {
        var _this = this;
        var cElement = document.createElement('div');
        cElement.className = 'item clearfix';
        if (args.buttons && Array.isArray(args.buttons)) {
            args.buttons.forEach(function(element){
                var item = document.createElement('div');
                item.className = element.className;
                item.innerHTML = '<i class="ok icon-check"></i>'
                    + '<span class="bg-white-circle"></span>'
                    + element.html
                    + '<div class="wrap"></div>';

                cElement.appendChild(item);

                item.addEventListener('click', _this._choosen);
                if (args.callback) item.addEventListener('click', args.callback);
            });
        } else {
            cElement.innerHTML = '<div class="' + args.className + '">'
                + '<i class="ok icon-check"></i>'
                + '<span class="bg-white-circle"></span>'
                + args.html
                + '<div class="wrap"></div>'
                + '</div>';
            cElement.children[0].addEventListener('click', _this._choosen);
        }

        return cElement;
    }
    /**
     * 
     * @private
     */
    , _choosen: function() {
        var choosen = this.parentElement.querySelector('.choosen');
        if (choosen) {
            choosen.classList.remove('choosen');
        }
        this.classList.add('choosen');
    }
    /**
     * 
     * @param args {Obj}
     * @returns {HTMLElement}
     * @private
     */
    , _inputImage: function(args) {
        var _this = this;
        args.elClass = args.elClass || '';
        var item = document.createElement('div');
        item.className = 'item clearfix nofloat nopadding ' + args.elClass;
        item.innerHTML = '<label>' + args.title + '</label>'
            + '<input type="text" class="choice-images" />'
            + '<i class="supra icon-folder-picture"></i>';

        item.querySelector('i').addEventListener('click', function() {
            var modGallery = new Modal('supra-modal-gallery', 'Gallery', {
                parentModal: _this
                , targetElement: item
            });
        });

        return item;
    }
    /**
     * 
     * @returns {HTMLElement}
     * @private
     */
    , _separator: function() {
        var separator = document.createElement('div');
        separator.className = 'separator-or';
        separator.innerHTML = '<hr>'
            + '<div class="wrap flex-center">'
            + '<span class="flex-center">OR</span>'
            + '</div>';
        return separator;
    }
    /**
     * 
     * @param {type} dropDown
     * @private
     */
    , _addEventListToDropdown: function(dropDown) {
        var options = dropDown.querySelectorAll('li a');
        var button = dropDown.querySelector('.dropdown button');
        Array.prototype.forEach.call(options, function(element){
            element.addEventListener('click', function(e){
                e.preventDefault();
                var val = element.innerHTML;
                button.dataset.value = replaceSpace(firstDown(val));
                button.querySelector('span').innerHTML = val;

                var eventCheckSelect = new CustomEvent(
                    'supra.check.select'
                    , {'detail': val}
                );
                dropDown.dispatchEvent(eventCheckSelect);
            });
        });
    }
    /**
     * 
     * @param {type} args
     * @returns {Modal.prototype._dropDown.dropDown|Element}
     * @private
     */
    , _dropDown: function(args) {
        var dropDown = document.createElement('div');
        dropDown.className = 'item clearfix';
        var ul = '<ul class="dropdown-menu" aria-labelledby="dropdownMenu' + this._countDropDown + '">';
        args.menu.forEach(function(element) {
            ul += '<li><a href="#">' + firstUp(element) + '</a></li>';
        });
        ul += '</ul>';

        var visibleCValue = args.menu[0] ? firstUp(args.menu[0]) : '';
        var curentValue = args.menu[0] ? args.menu[0] : '';
        if (args.callback !== undefined && args.callback() !== '') {
            var curentValue = args.callback();
            visibleCValue = firstUp(curentValue);
        }

        curentValue = replaceSpace(curentValue);

        var title = args.title !== '' ? '<label>' + args.title + '</label>' : '';

        dropDown.innerHTML = title
            + '<div class="dropdown">'
            + '<button class="supra-btn btn-default dropdown-toggle ' + args.elClass + '" ' +
            'type="button" id="dropdownMenu' + this._countDropDown + '"' +
            'data-toggle="dropdown" ' +
            'aria-haspopup="true" aria-expanded="false"' +
            'data-value="' + curentValue + '">'
            + '<span>' + visibleCValue + '</span>'
            +' <i class="icon-chevron-down"></i>'
            + '</button>'
            + ul
            + '</div>';

        this._addEventListToDropdown(dropDown);


        this._countDropDown ++;

        return dropDown;
    }
    /**
     * 
     * @param {type} args
     * @returns {Modal.prototype._switch.sw|Element}
     * @private
     */
    , _switch: function(args) {
        var sw = document.createElement('div');
        sw.className = 'item clearfix ' + args.elClass;
        var check = args.checked ? 'switch-on' : 'switch-off';
        var checkedInput = args.checked ? 'checked' : '';
        sw.innerHTML = '<div class="switch-group ' + args.type + '">' +
            '<label>' + args.title + '</label>' +
            '<div class="switch ' + check + '">'
            + '<input type="checkbox" name="switch" ' + checkedInput + '/>'
            + '<div class="wrap clearfix">'
            + '<span class="flex-center">ON</span>'
            + '<span class="switch-label flex-center"></span>'
            + '<span class="flex-center">OFF</span>'
            + '</div>'
            + '</div>' +
            '</div>';

        if (args.callback) args.callback(sw);

        return sw;
    }
    /**
     * 
     * @param {type} args
     * @returns {Modal.prototype._radio.radioGroup|Element}
     * @private
     */
    , _radio: function(args) {
        var radioGroup = document.createElement('div');
        radioGroup.className = 'item clearfix ' + args.marginTop;
        var items = '';
        args.items.forEach(function(name, indx) {
            var checked = indx === 0 ? 'checked' : '';
            items += '<label class="radio-inline">'
                + '<input type="radio" name="radio" value="' + name.toLowerCase().replace(/ /ig, '-') + '" ' + checked + '>'
                + '<span class="lbl">' + name + '</span>'
                + '</label>';
        });
        var title = args.title !== undefined && args.title !== '' ? '<label>' + args.title + '</label>' : '' ;
        radioGroup.innerHTML = title
            + '<div class="supra radio nomargintop">'
            + items
            + '</div>';

        return radioGroup;
    }
    /**
     * 
     * @param {type} args
     * @returns {Modal.prototype._checkbox.checkbox|Element}
     * @private
     */
    , _checkbox: function(args) {
        var checkbox = document.createElement('div');
        checkbox.className = 'item clearfix';
        checkbox.innerHTML = '<div class="supra checkbox">'
            + '<label>'
            + '<input type="checkbox" name="check">'
            + '<span class="lbl">' + args.name + '</span>'
            + '</label>'
            + '</div>';
        checkbox.querySelector('input').checked = args.checked;
        return checkbox;
    }
    /**
     * 
     * @param {type} args
     * @returns {Modal.prototype._figure.figure|Element}
     * @private
     */
    , _figure: function(args) {
        var _this = this;
        var figure = document.createElement('div');
        figure.className = 'item clearfix';
        figure.innerHTML = '<figure>'
            + '<div class="wrap-hover flex-center">'
            + '<img src="" alt="image" />'
            + '<div class="img" style="display: none;"></div>'
            + '<div class="bg-test bg"></div>'
            + '<i class="supra icon-folder-picture flex-center before-square"></i>'
            + '</div>'
            + '<figcaption>600x800</figcaption>'
            + '</figure>';

        figure.querySelector('i').addEventListener('click', function() {
            var modGallery = new Modal('supra-modal-gallery', 'Gallery', {
                parentModal: _this
                , targetElement: args.callback()
            });
        });

        var img = figure.querySelector('img');
        var figcaption = figure.querySelector('figcaption');
        img.addEventListener('load', function() {
            if (args.section) {
                var widthSection = args.section.getBoundingClientRect().width;
                var ptWidth = Math.round(this.naturalWidth / widthSection * 100);
                var divImg = figure.querySelector('.img');

                divImg.dataset.percent = ptWidth;
                if (args.sizeAuto) {
                    divImg.style.backgroundSize = ptWidth + '% auto';
                    divImg.style.webkitBackgroundSize = ptWidth + '% auto';
                }
            }
            figcaption.innerHTML = this.naturalWidth + 'x' + this.naturalHeight;
        });

        return figure;
    }
    /**
     * 
     * @param {type} args
     * @returns {Modal.prototype._inputText.input|Element}
     * @private
     */
    , _inputText: function(args) {
        var input = document.createElement('div');
        args.elClass = args.elClass || '';
        args.value = args.value || '';
        args.disabled = args.disabled || '';
        args.placeholder = args.placeholder || '';
        input.className = 'item clearfix nopadding nofloat ' + args.elClass;

        var title = args.title !== '' ? '<label>' + args.title + '</label>' : '';
        input.innerHTML = title
            + '<input type="text" class="choice-text ' + '" '
            + 'placeholder="' + args.placeholder + '" '
            + 'value="' + args.value +'" '
            + args.disabled + '>';
        return input;
    }
    /**
     * 
     * @param {type} args
     * @returns {Modal.prototype._inputRange.range|Element}
     * @private
     */
    , _inputRange: function(args) {
        var range = document.createElement('div');
        range.className = 'item clearfix';
        var opacity = args.opacity();
        if (opacity) {
            range.innerHTML = '<input type="range" value="' + opacity[1]*100 + '"/>';
        } else {
            range.innerHTML = '<input type="range" value="100"/>';
        }

        return range;
    }
    /**
     * 
     * @returns {Modal.prototype._pageSettinsButton.btnGroup|Element}
     * @private
     */
    , _pageSettinsButton: function() {
        var btnGroup = document.createElement('div');
        btnGroup.className = 'item clearfix';

        btnGroup.innerHTML = '<div class="btn-group gray-buttons-group" role="group" aria-label="...">'
            + '<button id="general" type="button" '
            + 'class="supra-btn btn-default-dark col-sm-4 col-md-4 col-lg-4 active">General</button>'
            + '<button id="seo" type="button" '
            + 'class="supra-btn btn-default-dark col-sm-4 col-md-4 col-lg-4">SEO</button>'
            + '<button id="s-preloader" type="button" '
            + 'class="supra-btn btn-default-dark col-sm-4 col-md-4 col-lg-4">Preloader</button>'
            + '</div>';

        var buttons = btnGroup.querySelectorAll('button');
        Array.prototype.forEach.call(buttons, function(element) {
            element.addEventListener('click', function() {
                builder.selection(this);
                var parent = controls.findParent(this, ['btn-page-control']);
                var className = parent.className;
                var pattern = new RegExp('(general|seo|s-preloader)','i');
                if (parent) parent.className = className.replace(pattern, this.id);
            });
        });
        return btnGroup;
    }
    /**
     * 
     * @param {type} args
     * @returns {Modal.prototype._textArea.textArea|Element}
     * @private
     */
    , _textArea: function(args) {
        var textArea = document.createElement('div');
        textArea.className = 'item clearfix' + args.elClass;

        textArea.innerHTML = '<div class="">'
            + '<label>' + args.title + '</label>'
            + '<textarea>' + args.value + '</textarea>'
            + '</div>';

        return textArea;
    }
    /**
     * 
     * @param {type} args
     * @returns {Modal.prototype._description.p|Element}
     * @private
     */
    , _description: function(args) {
        var p = document.createElement('div');
        p.className = 'item clearfix';

        p.innerHTML = '<p>' + args.value + '</p>';

        return p;
    }
    /**
     * 
     * @param {type} args
     * @returns {Modal.prototype._preloaderType.preloader|Element}
     * @private
     */
    , _preloaderType: function(args) {
        var _this = this;
        var preloader = document.createElement('div');
        preloader.id = 'prev-preload';
        preloader.className = 'item icons clearfix';

        preloader.innerHTML = '<label>' + args.title + '</label>';

        args.html.forEach(function(element, indx){
            preloader.appendChild(_this._preloaderItem(_this, element, args.dataName[indx], args.active));
        });

        return preloader;
    }
    /**
     * 
     * @param {type} args
     * @returns {Modal.prototype._galleryItems.gallery|Element}
     * @private
     */
    , _galleryItems: function(args) {
        var _this = this;
        var gallery = document.createElement('div');
        gallery.className = 'item ' + args.className + ' clearfix';
        if (args.className === 'gallery') {
            args.data.forEach(function (element) {
                gallery.appendChild(_this._getItemForGallery(element.name
                    , element.width
                    , element.height
                    , './images/gallery/' + element.name));
            });
        } else {
            args.data.forEach(function (element) {
                gallery.appendChild(_this._getItemForIconsGallery(element));
            });
        }

        return gallery;
    }
    /**
     * 
     * @param {type} _this
     * @param {type} el
     * @param {type} dataName
     * @param {type} activeItem
     * @returns {Modal.prototype._preloaderItem.item|Element}
     * @private
     */
    , _preloaderItem: function(_this, el, dataName, activeItem) {
        var item = document.createElement('div');
        var active = dataName === activeItem ? ' active' : '';
        item.dataset.value = dataName;
        item.className = 'choice-element flex-center flex-column' + active;
        item.innerHTML = el + '<i class="ok icon-check"></i><span class="bg-white-circle"></span>';

        item.addEventListener('click', function(){
            builder.selection(this);
            if (this.querySelector('.icon-picture')) {
                _this._body.classList.add('show-input-img');
            } else {
                if (_this._body.classList.contains('show-input-img')) {
                    _this._body.classList.remove('show-input-img');
                }
            }
        });

        return item;
    }
    /**
     * 
     * @param {type} _this
     * @returns {Modal.prototype._upload.upload|Element}
     * @private
     */
    , _upload: function(_this) {
        var upload = document.createElement('div');
        upload.className = 'upload';
        upload.innerHTML = '<button><i class="icon-cloud-upload"></i>Upload</button>'
            + '<input type="file" name="image" />';
        var inputFile = upload.querySelector('input');
        upload.querySelector('button').addEventListener('click', function() {
            inputFile.click();
        });
        inputFile.addEventListener('change', function() {

            if (inputFile.files && inputFile.files[0]) {
                var images = _this._elementsGallery;
                var nameFile = replaceSpace(inputFile.files[0].name);
                var index = 1;

                var form = new FormData();
                form.append('data', inputFile.files[0]);
                form.append('name_file', nameFile);
                builder.ajax(form, 'addgallery');

                images.forEach(function(element) {
                    if (nameFile === element.name) {
                        var nameArr = nameFile.split('.');
                        if (index > 1) {
                            var reg = /^(.*)(_[0-9]?)$/;
                            nameFile = nameArr[0].replace(reg, '$1_' + index + '.' + nameArr[1]);
                        } else {
                            nameFile = nameArr[0] + '_' + index + '.' + nameArr[1];
                        }
                        index++;
                    }
                });
                var reader = new FileReader();
                reader.readAsDataURL(inputFile.files[0]);

                reader.addEventListener('load', function(e) {
                    var gallery = _this._body.querySelector('.item.gallery');
                    var image = new Image();
                    image.src = e.target.result;

                    image.addEventListener('load', function() {

                        gallery.appendChild(_this._getItemForGallery(nameFile
                            , this.naturalWidth
                            , this.naturalHeight
                            , e.target.result
                            , './images/gallery/' + replaceSpace(inputFile.files[0].name)));
                    });
                });
            }
        });

        return upload;
    }
    /**
     * 
     * @param {type} name
     * @param {type} width
     * @param {type} height
     * @param {type} src
     * @param {type} path
     * @returns {Modal.prototype._getItemForGallery.item|Element}
     * @private
     */
    , _getItemForGallery: function(name, width, height, src, path) {
        var path = path || src;
        var item = document.createElement('figure');
        item.className = 'col-lg-2 selecting-item';
        item.dataset.src = path;

        var image = new Image();
        image.src = src;
        image.setAttribute('alt', 'section');
        var format = 1.085;
        if (window.innerWidth < 501) format = 0.75;
        this._imageSizig(width, height, format, image);

        item.innerHTML = '<div class="wrap-hover flex-center">'
            + '<i class="icon-check flex-center"></i>'
            + '</div>'
            + '<figcaption>'
            + '<p>' + name + '</p>'
            + '<p>' + width + 'x' + height + '</p>'
            + '</figcaption>';

        item.querySelector('.wrap-hover').appendChild(image);

        item.addEventListener('click', function(){
            builder.selection(this);
        });

        return item;
    }
    /**
     * 
     * @param {type} icon
     * @returns {Modal.prototype._getItemForIconsGallery.item|Element}
     * @private
     */
    , _getItemForIconsGallery: function(icon) {
        var item = document.createElement('div');
        item.className = 'ico choice-element flex-center';

        item.innerHTML = '<i class="ok icon-check"></i>'
            + '<span class="bg-white-circle"></span>'
            + '<i class="' + icon.slice(1) + '"></i>';

        item.addEventListener('click', function(){
            builder.selection(this);
        });
        return item;
    }
    /**
     * 
     * @param {type} width
     * @param {type} height
     * @param {type} format
     * @param {type} DOMimage
     * @returns {undefined}
     * @private
     */
    , _imageSizig: function(width, height, format, DOMimage) {
        if (height <= width && (width/height) > format) {
            DOMimage.style.width = '100%';
        } else {
            DOMimage.style.height = '100%';
            DOMimage.style.width = 'auto';
        }
    }
    /**
     * 
     * @param {type} classButton
     * @param {type} nameButton
     * @param {type} callback
     * @returns {Modal.prototype._getButton.button|Element}
     * @private
     */
    , _getButton: function(classButton, nameButton, callback) {
        var button = document.createElement('button');
        button.className = classButton;
        button.setAttribute('type', 'button');
        button.innerHTML = nameButton;

        button.addEventListener('click', function() {
            callback();
        });

        return button;
    }
    /**
     * -------------------------------------------- Part - create modal type ----------------------------------------
     */

    /**
     * To create modal dialog for tuning section, nav and popus background
     */
    
    /**
     * 
     * @private
     */
    , _getModalSectionBg: function (_this) {
        var li = this._targetObject;
        var bgStyleSelector = '#' + li.children[0].id + ' .bg';
        var section = li.children[0];
        var classParallax = '';

        //for popups
        if (controls.findParent(li, ['modal-dialog'])) {
            li = builder.editingSectionForm;
            var popup = controls.findParent(this._targetObject, ['modal']);
            bgStyleSelector = '#' + popup.id + ' .bg';
            section = this._targetObject;
            classParallax = 'hide';
        }
        this._title.innerHTML = '<h4>Background settings</h4>';

        this._elements = null;

        
        var style = li.querySelector('style').innerHTML;

        // for navigations
        if (li.classList.contains('nav')) {
            section = section.querySelector('.nav-bg');
            bgStyleSelector = '.nav-bg';
        }
        var bgStyleColor = '';
        var classBg = section.className.match('bg-([0-9]{1})-color-(light|dark)');
        if (classBg) {
            if (classBg[2] === 'light') {
                bgStyleColor = 'Light background ' + classBg[1];
            } else if (classBg[2] === 'dark') {
                bgStyleColor = 'Dark background ' + classBg[1];
            }
        } else {
            bgStyleColor = 'Light background 1';
        }

        var patternStyleSize = new RegExp(bgStyleSelector + '\\s?\{[ \\n\\t\\ra-z0-9:()\'\\/.;_-]*background-size:\\s*([^;]*);', 'im');
        var bgOptions = style.match(patternStyleSize);
        var patternStyleRepeat = new RegExp(bgStyleSelector + '\\s?\{[ \\n\\t\\ra-z0-9:()\'\\/.;_-]*background-repeat:\\s*([^;]*);', 'im');
        var repeat = style.match(patternStyleRepeat);

        if (!li.classList.contains('nav')) {
            _this._constructModalBody([
                    {
                        name: 'inputImage'
                        , func: 'inputImage'
                        , args: {
                        title: 'Background path'
                        , elClass: 'col-sm-9 col-md-9 col-lg-9'
                    }
                    }
                    , {
                        name: 'BgStyle'

                        , func: 'dropDown'
                        , args: {
                            menu: ['cover', 'auto', 'contain', 'repeat']
                            , title: 'Background style:'
                            , elClass: 'col-sm-9 col-md-9 col-lg-9'
                            , callback: function() {
                                if (repeat && repeat[1] === 'repeat') {
                                    return repeat[1];
                                }
                                return  bgOptions ? bgOptions[1] : 'Auto';
                            }
                        }
                    }
                    , {
                        name: 'BgColor'
                        , func: 'dropDown'
                        , args: {
                            menu: [
                                'Light background 1'
                                , 'Light background 2'
                                , 'Light background 3'
                                , 'Dark background 1'
                                , 'Dark background 2'
                                , 'Dark background 3'
                            ]
                            , title: 'Background color:'
                            , elClass: 'col-sm-9 col-md-9 col-lg-9'
                            , callback: function() {
                                return  bgStyleColor;
                            }
                        }
                    },
                    {
                        name: 'parallax'
                        , func: 'switch'
                        , args: {
                        title: 'Parallax'
                        , type: ''
                        , checked: li.classList.contains('parallax')
                        , elClass: classParallax
                        , callback: function (sw) {
                            sw.querySelector('.switch').addEventListener('click', function (e) {
                                e.preventDefault();
                                if (this.classList.contains('switch-on')) {
                                    this.classList.remove('switch-on');
                                    this.classList.add('switch-off');
                                    this.querySelector('input').removeAttribute('checked');
                                } else {
                                    this.classList.remove('switch-off');
                                    this.classList.add('switch-on');
                                    this.querySelector('input').setAttribute('checked', '');
                                }
                            });
                        }
                    }
                    }
                ], 'col-sm-6 col-md-6 col-lg-6 nopadding'
            );
        } else {
            _this._constructModalBody([
                    {
                        name: 'BgColor'
                        , func: 'dropDown'
                        , args: {
                        menu: [
                            'Light background 1'
                            , 'Light background 2'
                            , 'Light background 3'
                            , 'Dark background 1'
                            , 'Dark background 2'
                            , 'Dark background 3'
                        ]
                        , title: 'Background color:'
                        , elClass: 'col-sm-9 col-md-9 col-lg-9'
                        , callback: function () {
                            return bgStyleColor;
                        }
                    }
                    }
                ], 'col-sm-6 col-md-6 col-lg-6 nopadding'
            );
        }

        var patternOpacity = new RegExp(bgStyleSelector + '[\\s]*\{[ \\n\\t\\ra-z0-9:()\'\\/.;_-]*opacity:[\\s]*([^;]*)', 'im');
        var opacity = style.match(patternOpacity);

        _this._constructModalBody([
                {
                    name: 'figure'
                    , func: 'figure'
                    , args: {
                    callback: function() {
                        return _this._elements.inputImage;
                    }
                    , section: section
                    , sizeAuto: !bgOptions || (bgOptions && bgOptions[1] === 'auto')
                }
                }
                , {
                    name: 'inputRange'
                    , func: 'inputRange'
                    , args: {
                        opacity: function() {
                            return opacity;
                        }
                    }
                }

            ], 'col-sm-6 col-md-6 col-lg-6 nopadding preview-bg'
        );

        var img = _this._elements.figure.querySelector('img');
        var divImg = _this._elements.figure.querySelector('.img');
        divImg.style.display = 'block';

        $(_this._selfDOM).on('shown.bs.modal', function() {
            var widthSection = section.getBoundingClientRect().width;
            var widthModal = _this._elements.figure.getBoundingClientRect().width;
            var percentWidth =  Math.round(widthModal / widthSection * 100);
            var heightSection = section.getBoundingClientRect().height;
            var heightModal = heightSection * (percentWidth/100);
            var wrap = _this._elements.figure.querySelector('.wrap-hover');
            wrap.style.height = heightModal + 'px';
        });

        var patternImage = new RegExp(bgStyleSelector + ' ?\{[ \\n\\t\\ra-z0-9:()\'\\/.;_-]*background-image:\\s*url\\(\'?/?([^\']*)\'?\\);', 'im');

        var src = (style.match(patternImage) && style.match(patternImage)[1] !== '') ? style.match(patternImage)[1] : '';

        if (!li.classList.contains('nav')) {
            _this._elements.inputImage.querySelector('input').value = src;

            if (src !== '') {
                img.src = src;
                divImg.style.backgroundImage = 'url(\'' + src + '\')';
            }

            if (repeat && repeat[1] === 'repeat') {
                divImg.style.backgroundRepeat = 'repeat';
                divImg.style.webkitBackgroundRepeat = 'repeat';
            } else {
                divImg.style.backgroundRepeat = 'no-repeat';
                divImg.style.webkitBackgroundRepeat = 'no-repeat';
            }
            if (bgOptions && bgOptions[1] !== 'auto') {
                divImg.style.backgroundSize = bgOptions[1];
                divImg.style.webkitBackgroundSize = bgOptions[1];
            }

            var bgStyle = _this._elements.BgStyle;
            bgStyle.addEventListener('supra.check.select', function (e) {
                if (e.detail.toLowerCase() === 'repeat') {
                    divImg.style.backgroundRepeat = 'repeat';
                    divImg.style.webkitBackgroundRepeat = 'repeat';
                } else {
                    divImg.style.backgroundRepeat = 'no-repeat';
                    divImg.style.webkitBackgroundRepeat = 'no-repeat';
                }
                if (e.detail.toLowerCase() === 'auto' || e.detail.toLowerCase() === 'repeat') {
                    divImg.style.backgroundSize = divImg.dataset.percent + '% auto';
                    divImg.style.webkitBackgroundSize = divImg.dataset.percent + '% auto';
                } else {
                    divImg.style.backgroundSize = e.detail.toLowerCase();
                    divImg.style.webkitBackgroundSize = e.detail.toLowerCase();
                }
            });

            divImg.style.opacity = opacity ? opacity[1] : 1;

            var range = _this._elements.inputRange.querySelector('input');
            range.addEventListener('input', function () {
                divImg.style.opacity = this.value / 100;
            });
        }



        var background = this._elements.figure.querySelector('.bg-test');
        var bgClassName = section.className.match(/bg-.-color-(light|dark)/i);
        if (bgClassName) background.classList.add(bgClassName[0]);
        var bgElementColor = _this._elements.BgColor;
        bgElementColor.addEventListener('supra.check.select', function(e) {
            var bgColor = e.detail.toLowerCase().split(' ');

            _this._chooseBgColor(bgColor, background);
        });

        if (li.classList.contains('nav')) {
            background.style.opacity = opacity ? opacity[1] : 1;

            var range = _this._elements.inputRange.querySelector('input');
            range.addEventListener('input', function () {
                background.style.opacity = this.value / 100;
            });

            var hover = _this._elements.figure.querySelector('i');
            hover.style.display = 'none';
        }

        var argsSave = {
            image: _this._elements.inputImage ? _this._elements.inputImage.querySelector('input').value : ''
            , bgColor: _this._elements.BgColor.querySelector('button').dataset.value.toLowerCase().split('_')
            , bgStyle: _this._elements.BgStyle ? _this._elements.BgStyle.querySelector('.dropdown button').dataset.value.toLowerCase() : ''
            , parallax: _this._elements.parallax ? _this._elements.parallax.querySelector('input').checked : ''
            , range: _this._elements.inputRange.querySelector('input').value / 100
        };

        //this is need to create new button because modal-footer will be overloaded
        this._footer.innerHTML = '<button type="button" class="supra-btn btn-default" data-dismiss="modal">Cancel</button>';

        var apply = this._getButton('supra-btn btn-primary', 'Apply', function() {
            var args = {
                image: _this._elements.inputImage ? _this._elements.inputImage.querySelector('input').value : ''
                , bgColor: _this._elements.BgColor.querySelector('button').dataset.value.toLowerCase().split('_')
                , bgStyle: _this._elements.BgStyle ? _this._elements.BgStyle.querySelector('.dropdown button').dataset.value.toLowerCase() : ''
                , parallax: _this._elements.parallax ? _this._elements.parallax.querySelector('input').checked : ''
                , range: _this._elements.inputRange.querySelector('input').value / 100
            };

            _this._applySectionBg(_this, li, args, argsSave);

            $(_this._selfDOM).modal('hide');
        });
        this._footer.appendChild(apply);
    }
    /**
     * 
     * @private
     */ 
    , _applySectionBg: function(_this, li, args, argsSave) {
        args.image = builder.replaceQuotes(args.image);
        if (li.classList.contains('nav')) {
            var i = 0;
            while(li.children[i].nodeName !== 'STYLE') {
                if (i === 0) {
                    var section = li.children[i].querySelector('.nav-bg');
                } else {
                    var section = li.children[i];
                }
                _this._chooseBgColor(args.bgColor, section);
                i++;
            }

            var bgStyleSelector = '.nav-bg';
            var pattern = new RegExp('(' + bgStyleSelector + ' ?\{)([^\}]*)(\})', 'im');
            var style = li.querySelector('style');

            if (style.innerHTML.search(pattern) !== -1) {
                style.innerHTML = style.innerHTML.replace(pattern, '$1\n'
                    + '\topacity: ' + args.range + ';\n$3');
            } else {
                style.innerHTML = bgStyleSelector + ' {\n '
                    + '\topacity: ' + args.range + ';\n'
                    + '}'
                    + style.innerHTML;
            }
        } else {
            var bgStyleSelector = '#' + li.children[0].id + ' .bg';
            var section = li.children[0];
            if (controls.findParent(this._targetObject, ['modal-dialog'])) {
                var popup = controls.findParent(this._targetObject, ['modal']);
                bgStyleSelector = '#' + popup.id + ' .bg';
                section = this._targetObject;
                
            }
            var pattern = new RegExp('(' + bgStyleSelector + ' ?\{)([^\}]*)(\})', 'im');
            var bgOptionSize = args.bgStyle;
            var bgOptionRepeat = 'no-repeat';
            if (args.bgStyle === 'repeat') {
                bgOptionSize = 'auto';
                bgOptionRepeat = args.bgStyle;
            }
            var style = li.querySelector('style');

            if (style.innerHTML.search(pattern) !== -1) {
                style.innerHTML = style.innerHTML.replace(pattern, '$1'
                    + _this._getBgStyle(args.image, bgOptionSize, bgOptionRepeat, args.range)
                    + '$3');
            } else {
                style.innerHTML = '\n' + bgStyleSelector + ' {'
                    + _this._getBgStyle(args.image, bgOptionSize, bgOptionRepeat, args.range)
                    + '}\n'
                    + li.children[1].innerHTML;
            }
            
            _this._chooseBgColor(args.bgColor, section);
            var bg = section.querySelector('.bg');

            if (args.parallax) {
                if (!li.classList.contains('parallax')) {
                    li.classList.add('parallax');

                    if (!bg.classList.contains('parallax-bg')) {
                        bg.classList.add('parallax-bg');
                    }
                    bg.dataset.topBottom = 'transform:translate3d(0px, 25%, 0px)';
                    bg.dataset.bottomTop = 'transform:translate3d(0px, -25%, 0px)';
                    if (skr) {
                        skr.refresh();
                    }
                }
            } else {
                if (li.classList.contains('parallax')) {
                    li.classList.remove('parallax');
                    bg.removeAttribute('style');
                    for (var indx in bg.dataset) {
                        delete bg.dataset[indx];
                    }
                    bg.classList.remove('skrollable');
                    bg.classList.remove('skrollable-between');
                    bg.classList.remove('parallax-bg');
                    if (skr) skr.refresh();
                }
            }
        }
        builder.setStep(function () {
            _this._applySectionBg(_this, li, argsSave, args);
        });
    }
    /**
     * 
     * @private
     */ 
    , _chooseBgColor: function(bgColor, bg) {
        bg.className = bg.className.replace(/bg-.-color-(light|dark)/i, '');
        bg.classList.add('bg-' + bgColor[2] + '-color-' + bgColor[0]);
    }
    /**
     * 
     * @private
     */ 
    , _getModalSectionSettings: function (_this) {
        var li = _this._targetObject;
        var section = li.children[0];

        this._title.innerHTML = '<h4>Section settings</h4>';

        this._elements = null;

        _this._constructModalBody([
                {
                    name: 'PadTop'
                    , func: 'dropDown'
                    , args: {
                    menu: ['0px', '25px', '50px', '75px', '100px', '125px', '150px', '200px', '250px']
                    , title: 'Padding top:'
                    , elClass: 'col-sm-12 col-md-12 col-lg-12'
                    , callback: function() {
                        var section = _this._targetObject.children[0];
                        var cNameTop = section.className.match(/pt-([^ ]*)/i);
                        return  cNameTop ? cNameTop[1] + 'px' : '0px';
                    }
                }
                }
                , {
                    name: 'PadBottom'
                    , func: 'dropDown'
                    , args: {
                        menu: ['0px', '25px', '50px', '75px', '100px', '125px', '150px', '200px', '250px']
                        , title: 'Padding bottom:'
                        , elClass: 'col-sm-12 col-md-12 col-lg-12'
                        , callback: function() {
                            var section = _this._targetObject.children[0];
                            var cNameBottom = section.className.match(/pb-([^ ]*)/i);
                            return  cNameBottom ? cNameBottom[1] + 'px' : '0px';
                        }
                    }
                }
            ], 'col-sm-6 col-md-6 col-lg-6 section-set-dropdown'
        );

        var skin = false;
        if (section.classList.contains('dark')) {
            skin = true;
        }

        var separator = false;
        if (section.classList.contains('separator-bottom')) {
            separator = true;
        }

        _this._constructModalBody([
            {
                name: 'separator'
                , func: 'dropDown'
                , args: {
                    title: 'Separator:'
                    , menu: ['None', 'Content width', 'Screen width']
                    , elClass: 'separator'
                    , callback: function() {
                        if (section.className.search(/sep-/) !== -1) {
                            var classSep = section.className.match(/sep-[^\s]*/);
                            if (classSep[0] === 'sep-b') {
                                return 'content width';
                            } else {
                                return 'screen width';
                            }
                        }
                        return 'none';
                    }
                }
            }
            , {
                name: 'skin'
                , func: 'switch'
                , args: {
                    title: 'Dark skin:'
                    , type: ''
                    , checked: skin
                    , callback: function(sw) {
                        sw.querySelector('.switch').addEventListener('click', function(e) {
                            e.preventDefault();
                            if (this.classList.contains('switch-on')) {
                                this.classList.remove('switch-on');
                                this.classList.add('switch-off');
                                this.querySelector('input').removeAttribute('checked');
                            } else {
                                this.classList.remove('switch-off');
                                this.classList.add('switch-on');
                                this.querySelector('input').setAttribute('checked' , '');
                            }
                        });
                    }
                }
            }

            ], 'col-sm-6 col-md-6 col-lg-6 nopadding section-settings'
        );

        var argsSave = {
            paddingTop: _this._elements.PadTop.querySelector('button').dataset.value
            , paddingBottom: _this._elements.PadBottom.querySelector('button').dataset.value
            , skin: _this._elements.skin.querySelector('input').checked
            , separator: _this._elements.separator.querySelector('.dropdown button').dataset.value.replace(/_/i, ' ')
        };

        //this is need to create new button because modal-footer will be overloaded
        this._footer.innerHTML = '<button type="button" class="supra-btn btn-default" data-dismiss="modal">Cancel</button>';

        var apply = this._getButton('supra-btn btn-primary', 'Apply', function() {
            var args = {
                paddingTop: _this._elements.PadTop.querySelector('button').dataset.value
                , paddingBottom: _this._elements.PadBottom.querySelector('button').dataset.value
                , skin: _this._elements.skin.querySelector('input').checked
                , separator: _this._elements.separator.querySelector('.dropdown button').dataset.value.replace(/_/i, ' ')
            };

            _this._applySectionSettings(_this, li, args, argsSave);

            $(_this._selfDOM).modal('hide');
        });
        this._footer.appendChild(apply);
    }
    /**
     * 
     * @private
     */ 
    , _applySectionSettings: function(_this, li, args, argsSave) {
        var section = li.children[0];
        var cNameTop = section.className.match(/pt-[^ ]*/i);
        var cNameBottom = section.className.match(/pb-[^ ]*/i);
        if (cNameTop && cNameTop[0] !== undefined) {
            section.classList.remove(cNameTop[0]);
        }
        section.classList.add('pt-' + args.paddingTop.substr(0, args.paddingTop.length-2));


        if (cNameBottom && cNameBottom[0] !== undefined) {
            section.classList.remove(cNameBottom[0]);
        }
        section.classList.add('pb-' + args.paddingBottom.substr(0, args.paddingBottom.length-2));

        if (li.style.position === 'fixed') {
            setTimeout(function() {
                li.style.height = li.children[0].getBoundingClientRect().height + 'px';
            },600);
        }
        var i = 0;
        while (li.children[i].nodeName !== 'STYLE') {
            _this._skinCheck(_this, args.skin, li.children[i], 'light', 'dark',' light|^light', ' dark|^dark');
            i++;
        }

        var form = li.querySelector('form');
        if (form) {
            var success = document.getElementById(section.id + '-success');
            var error = document.getElementById(section.id + '-error');
            if (success) {
                _this._skinCheck(_this, args.skin, success, 'light', 'dark',' light|^light', ' dark|^dark');
            }

            if (error) {
                _this._skinCheck(_this, args.skin, error, 'light', 'dark',' light|^light', ' dark|^dark');
            }

        }

        if (args.separator) {
            if (section.className.search(/sep-/) !== -1) {
                var classSep = section.className.match(/sep-[^\s]*/);
                section.classList.remove(classSep[0]);
            }
            if (args.separator === 'content width') {
                section.classList.add('sep-b');
            } else if (args.separator === 'screen width') {
                section.classList.add('sep-full-b');
            }
        }

        builder.setStep(function () {
            _this._applySectionSettings(_this, li, argsSave, args);
        });
    }
    /**
     * 
     * @private
     */ 
    , _getModalNavSectionSettings: function (_this) {
        var li = _this._targetObject;
        var section = li.children[0];

        this._title.innerHTML = '<h4>Navigation settings</h4>';

        this._elements = null;

        var skin = false;
        if (section.classList.contains('dark')) {
            skin = true;
        }

        _this._constructModalBody([
                {
                    name: 'type'
                    , func: 'dropDown'
                    , args: {
                    title: 'Type:'
                    , menu: [
                          'Default'
                        , 'Absolute'
                        , 'Absolute - double padding'
                        , 'Fixed'
                        , 'Fixed - slide start'
                        , 'Fixed - transperent start'
                        , 'Fixed - transparent and double padding start'
                    ]
                    , elClass: 'col-sm-12 col-md-10 col-lg-10'
                    , callback: function() {
                        var patternNavbar = new RegExp('navbar-[^\\s]*[\\s]*' , 'ig');
                        var patternNav = new RegExp('nav-[^\\s]*[\\s]*' , 'ig');
                        var matchNavbar = section.className.match(patternNavbar);
                        var matchNav = section.className.match(patternNav);
                        if (matchNavbar) {
                            switch (matchNavbar[0].trim()) {
                                case 'navbar-absolute-top':
                                    if (!matchNav) {
                                        return 'absolute';
                                    } else if (matchNav[0].trim() === 'nav-start-double-pad') {
                                        return 'absolute - double padding';
                                    }
                                    break;
                                case 'navbar-fixed-top':
                                    if (!matchNav) {
                                        return 'fixed';
                                    } else if (matchNav[0].trim() === 'nav-start-hide') {
                                        return 'fixed - slide start';
                                    } else if (matchNav[0].trim() === 'nav-start-hide-bg') {
                                        if (matchNav[1] && matchNav[1].trim() === 'nav-start-double-pad') {
                                            return 'fixed - transparent and double padding start';
                                        }
                                        return 'fixed - transperent start';
                                    }
                                    break;
                            }
                        }
                        return 'default';
                    }
                }
                }
                , {
                    name: 'skin'
                    , func: 'switch'
                    , args: {
                        title: 'Dark skin:'
                        , type: ''
                        , checked: skin
                        , callback: function(sw) {
                            sw.querySelector('.switch').addEventListener('click', function(e) {
                                e.preventDefault();
                                if (this.classList.contains('switch-on')) {
                                    this.classList.remove('switch-on');
                                    this.classList.add('switch-off');
                                    this.querySelector('input').removeAttribute('checked');
                                } else {
                                    this.classList.remove('switch-off');
                                    this.classList.add('switch-on');
                                    this.querySelector('input').setAttribute('checked' , '');
                                }
                            });
                        }
                    }
                }

            ], 'col-sm-12 col-md-12 col-lg-12 nopadding item-width-50 item-margin-top-0'
        );

        var argsSave = {
              skin: _this._elements.skin.querySelector('input').checked
            , type: _this._elements.type.querySelector('.dropdown button').dataset.value.replace(/_/ig, ' ')
        };

        //this is need to create new button because modal-footer will be overloaded
        this._footer.innerHTML = '<button type="button" class="supra-btn btn-default" data-dismiss="modal">Cancel</button>';

        var apply = this._getButton('supra-btn btn-primary', 'Apply', function() {
            var args = {
                skin: _this._elements.skin.querySelector('input').checked
                , type: _this._elements.type.querySelector('.dropdown button').dataset.value.replace(/_/ig, ' ')
            };

            _this._applyNavSectionSettings(_this, li, args, argsSave);

            $(_this._selfDOM).modal('hide');
        });
        this._footer.appendChild(apply);
    }
    /**
     * 
     * @private
     */ 
    , _applyNavSectionSettings: function(_this, li, args, argsSave) {
        var section = li.children[0];

        var i = 0;
        while (li.children[i].nodeName !== 'STYLE') {
            _this._skinCheck(_this, args.skin, li.children[i], 'light', 'dark',' light|^light', ' dark|^dark');
            i++;
        }

        if (args.type) {
            var triggerScroll = false;
            window.removeEventListener('scroll', builder.listenerSrcollTopForNav);
            section.style.transition = 'all 0s ease 0s';
            var patternNavbar = new RegExp('navbar-[^\\s]*[\\s]*' , 'ig');
            var patternNav = new RegExp('nav-[^\\s]*[\\s]*' , 'ig');
            section.className = section.className.replace(patternNavbar, '').trim();
            section.className = section.className.replace(patternNav, '').trim();
            switch (args.type) {
                case 'absolute':
                    section.className = section.className + ' navbar-absolute-top';
                    break;
                case 'absolute - double padding':
                    section.className = section.className + ' navbar-absolute-top nav-start-double-pad';
                    break;
                case 'fixed':
                    section.className = section.className + ' navbar-fixed-top';
                    break;
                case 'fixed - slide start':
                    section.className = section.className + ' navbar-fixed-top nav-start-hide';
                    break;
                case 'fixed - transperent start':
                    section.className = section.className + ' navbar-fixed-top nav-start-hide-bg';
                    break;
                case 'fixed - transparent and double padding start':
                    section.className = section.className + ' navbar-fixed-top nav-start-hide-bg nav-start-double-pad';
                    triggerScroll = true;
                    window.section = section;
                    break;
            }
            var position = window.getComputedStyle(section, null).getPropertyValue("position");

            li.removeAttribute('style');
            builder.setPosition(section, li, position, 1095);

            if (args.type === 'absolute' || args.type === 'absolute - double padding') {
                li.style.left = '50px';
            }

            var k = 1;

            if (section.className.search(/nav-start-double-pad/) !== -1) k = 3;
            var heightLi = section.getBoundingClientRect().height * k;
            li.style.height = heightLi + 'px';

            setTimeout(function(){
                section.style.removeProperty('transition');
            }, 100);

            if (triggerScroll) {
                window.addEventListener('scroll', builder.listenerSrcollTopForNav);
            }
        }

        builder.setStep(function () {
            _this._applyNavSectionSettings(_this, li, argsSave, args);
        });
    }
    /**
     * 
     * @private
     */ 
    , _skinCheck: function(_this, skin, element, light, dark, pLight, pDark) {
        pLight = pLight || light;
        pDark = pDark || dark;
        if (element.tagName === 'NAV' && light !== 'light') element = element.querySelector('.nav-bg');
        if (skin) {
            if (element.className.search(RegExp(pLight, 'i')) !== -1) {
                element.className = element.className.replace(RegExp(pLight + '\\s*', 'i'), ' ');
            }
            element.classList.add(dark);
        } else {
            if (element.className.search(RegExp(pDark, 'i')) !== -1) {
                element.className = element.className.replace(RegExp(pDark + '\\s*', 'i'), ' ');
            }
            element.classList.add(light);
        }
    }
    /**
     * 
     * @private
     */ 
    , _getModalGallery: function (_this) {
        this._title.innerHTML = '<h4>Choose image</h4>';
        this._title.appendChild(this._upload(_this));
        this._elementsGallery = null;

        builder.ajax(null, 'getgallery', function(data) {
            data = JSON.parse(data);
            _this._constructModalBody([
                    {
                        name: 'gallery'
                        , func: 'galleryItems'
                        , args: {data: data.gallery, className: 'gallery'}
                    }
                ], 'col-sm-12 col-md-12 col-lg-12 nopadding'
            );
            _this._elementsGallery = data.gallery;
            $(_this._selfDOM).modal('show');
        });

        this._body.classList.add('height-500');

        //this is need to create new button because modal-footer will be overloaded
        this._footer.innerHTML = '<button type="button" class="supra-btn btn-default" data-dismiss="modal">Cancel</button>';

        var apply = this._getButton('supra-btn btn-primary', 'Apply', function() {
            if (_this._elements.gallery.getElementsByClassName('active')) {
                var modal = _this._targetObject;
                var selectedImg = _this._elements.gallery.getElementsByClassName('active')[0];
                if (selectedImg) {
                    var src = selectedImg.dataset.src;
                    var postfix = '';
                    if (modal.targetElement.classList.contains('retina')) postfix += ' 2x';
                    modal.targetElement.querySelector('input').value = src + postfix;
                    var figure = modal.parentModal._elements.figure;
                    if (figure) {
                        var img = figure.querySelector('img');
                        var divImg = figure.querySelector('.img');

                        img.src = src;
                        divImg.style.backgroundImage = 'url(\'' + src + '\')';
                    }
                }
            }

            $(_this._selfDOM).modal('hide');
        });
        this._footer.appendChild(apply);

    }
    /**
     * 
     * @private
     */ 
    , _getModalIconsGallery: function (_this) {
        this._title.innerHTML = '<h4>Choose icon</h4>';
        this._elementsGallery = null;

        builder.ajax(null, 'geticonsgallery', function(data) {
            data = JSON.parse(data);
            _this._constructModalBody([
                    {
                        name: 'gallery'
                        , func: 'galleryItems'
                        , args: {data: data.iconsGallery[0], className: 'icons'}
                    }
                ], 'col-sm-12 col-md-12 col-lg-12 padding-top-10'
            );
            _this._elementsGallery = data.gallery;
        });

        this._header.style.paddingBottom = '19px';
        this._body.classList.add('height-500');
        this._body.classList.add('margin-top--10');

        //this is need to create new button because modal-footer will be overloaded
        this._footer.innerHTML = '<button type="button" class="supra-btn btn-default" data-dismiss="modal">Cancel</button>';

        var apply = this._getButton('supra-btn btn-primary', 'Apply', function() {
            if (_this._elements.gallery.getElementsByClassName('active')) {
                var icon = _this._targetObject;
                var newIconClass = _this._elements.gallery.querySelectorAll('.active i')[1].className;
                var className = icon.className;
                _this._applyIconsGallery(_this, icon, newIconClass, className);
            }

            $(_this._selfDOM).modal('hide');
        });
        this._footer.appendChild(apply);
    }
    /**
     * 
     * @private
     */ 
    , _applyIconsGallery: function(_this, icon, newIconClass, className) {
        var pattern = new RegExp('icon-(?!size|position|color)[^ ]*','i');
        icon.className = className.replace(pattern, newIconClass);

        builder.setStep(function() {
            _this._applyIconsGallery(_this, icon, className, newIconClass);
        });
    }
    /**
     * 
     * @private
     */ 
    , _getModalDelete: function(_this) {
        _this._header.innerHTML = '<h5 class="text-center">Are you sure you want to delete this section?</h5>';
        _this._body.classList.add('nopadding');

        //this is need to create new button because modal-footer will be overloaded
        this._footer.innerHTML = '<button type="button" class="supra-btn btn-default" data-dismiss="modal">Cancel</button>';

        var apply = this._getButton('supra-btn btn-danger', 'Delete', function() {
            var page = _this._targetObject.page;
            var section = _this._targetObject.section;

            page.deleteSection(section);

            $(_this._selfDOM).modal('hide');
        });
        this._footer.appendChild(apply);
    }
    /**
     * 
     * @private
     */ 
    , _getModalDeleteElement: function(_this) {
        _this._header.innerHTML = '<h5 class="text-center">Are you sure you want to delete this element?</h5>';
        _this._body.classList.add('nopadding');

        //this is need to create new button because modal-footer will be overloaded
        this._footer.innerHTML = '<button type="button" class="supra-btn btn-default" data-dismiss="modal">Cancel</button>';

        var apply = this._getButton('supra-btn btn-danger', 'Delete', function() {
            var DOMElement = _this._targetObject;

            builder.getActivePageObject().deleteElement(DOMElement);

            $(_this._selfDOM).modal('hide');
        });
        this._footer.appendChild(apply);
    }
    /**
     * 
     * @private
     */ 
    , _getModalDeleteProject: function(_this) {
        _this._header.innerHTML = '<h5 class="text-center">If you start new project, current project will be deleted. Are you sure you want to start new project?</h5>';
        _this._body.classList.add('nopadding');

        //this is need to create new button because modal-footer will be overloaded
        this._footer.innerHTML = '<button type="button" class="supra-btn btn-default" data-dismiss="modal">Cancel</button>';

        var apply = this._getButton('supra-btn btn-primary', 'Start new project', function() {

            builder.createNewProject();

            $(_this._selfDOM).modal('hide');
        });
        this._footer.appendChild(apply);
    }
    /**
     * 
     * @private
     */ 
    , _getModalReplace: function(_this) {
        var page = _this._targetObject;
        var key = Object.keys(page.sections[builder.sectionClicked.dataset.group])[0];
        //var section = page.sections[builder.sectionClicked.dataset.group][key].html;
        var section = page.getDOMSelf().querySelector('#' + key).parentElement;

        var nameSection = 'footer';
        if (section.classList.contains('nav') && builder) {
            nameSection = 'header';
        }

        _this._header.innerHTML = '<h5 class="flex-center">You can add only one navigation per page.</h5>'
            + '<h5 class="flex-center">Do you want raplace ' + nameSection + '?</h5>';
        _this._body.classList.add('nopadding');

        //this is need to create new button because modal-footer will be overloaded
        this._footer.innerHTML = '<button type="button" class="supra-btn btn-default" data-dismiss="modal">Cancel</button>';

        var apply = this._getButton('supra-btn btn-danger', 'Replace', function() {

            page.deleteSection(section, null, null, 'replace');
            if (document.body.classList.contains('off-canvas-active')) {
                document.body.classList.remove('off-canvas-active');
            }

            if (section.classList.contains('nav') && builder) {
                page.addSection(builder.sectionClicked, 'nav', builder.defaultStyleType);
            } else if (section.classList.contains('footer') && builder) {
                page.addSection(builder.sectionClicked, 'footer', builder.defaultStyleType);
            }

            $(_this._selfDOM).modal('hide');
        });
        this._footer.appendChild(apply);
    }
    /**
     * 
     * @private
     */ 
    , _getModalButtonSettings: function(_this) {
        var button = _this._targetObject;

        this._title.innerHTML = '<h4>Background settings</h4>';

        this._elements = null;

        var patternDef = new RegExp('(\\.' + builder.defaultStyleType + '-modal .choice-element .btn-default [\\s\\t\\w#.-]*{[\\s\\n\\t]*)'
            + '.*[\\s\\n\\t]*border-color:\\s*([^;]*)[^}]*(})', 'im');
        var defColor = builder.modalContainerStyleHtml.innerHTML.match(patternDef);
        var classBgDefButton = defColor[2] === '#ffffff' ? 'dark' : '';
        var patternPrm = new RegExp('(\\.' + builder.defaultStyleType + '-modal .choice-element .btn-primary [\\s\\t\\w#.-]*{[\\s\\n\\t]*)'
            + '.*[\\s\\n\\t]*border-color:\\s*([^;]*)[^}]*(})', 'im');
        var prmColor = builder.modalContainerStyleHtml.innerHTML.match(patternPrm);
        var classBgPrimButton = prmColor[2] === '#ffffff' ? 'dark' : '';
        var arsButton = [
            {
                className: 'choice-element'
                , btnClass: 'btn-success'
            }
            , {
                className: 'choice-element ' + classBgPrimButton
                , btnClass: 'btn-primary'
            }
            , {
                className: 'choice-element'
                , btnClass: 'btn-danger'
            }
            , {
                className: 'choice-element'
                , btnClass: 'btn-warning'
            }
            , {
                className: 'choice-element'
                , btnClass: 'btn-info'
            }
            , {
                className: 'choice-element'
                , btnClass: 'btn-link'
            }
            , {
                className: 'choice-element ' + classBgDefButton
                , btnClass: 'btn-default'
            }
            , {
                className: 'choice-element'
                , btnClass: 'btn-image'
                , html: '<i class="icon-picture"></i><span>Image</span>'
            }
        ];

        arsButton.forEach(function(element) {
            var content = element.html || 'Text';
            var classImageBtn = element.btnClass === 'btn-image' ? ' flex-center flex-column' : '';
            element.html = '<div class="btn ' + element.btnClass + classImageBtn +'">' + content + '</div>';
            if (button.classList.contains(element.btnClass)) {
                element.className += ' choosen';
            }
        });
        
        if (button.classList.contains('btn-image')) {
            _this._body.classList.add('show-input-img');
        }

        _this._constructModalBody([
                {
                    name: 'button'
                    , func: 'choiceElement'
                    , args: {
                        buttons: arsButton
                        , callback: function() {
                            if (this.querySelector('.btn-image')) {
                                _this._body.classList.add('show-input-img');
                            } else {
                                if (_this._body.classList.contains('show-input-img')) {
                                    _this._body.classList.remove('show-input-img');
                                }
                            }
                        }
                    }

                }
            ], 'col-sm-12 col-md-12 col-lg-12 nopadding btn-image-control margin-bottom--20'
        );

        _this._constructModalBody([
                {
                    name: 'size'
                    , func: 'dropDown'
                    , args: {
                        menu: ['Large', 'Default', 'Small', 'Extra small']
                        , title: 'Button size:'
                        , elClass: 'col-sm-11 col-md-11 col-lg-11'
                        , callback: function() {
                            if (button.className.search(/btn-lg/i) !== -1) {
                                return 'Large';
                            } else if (button.className.search(/btn-sm/i) !== -1) {
                                return 'Small';
                            } else if (button.className.search(/btn-xs/i) !== -1) {
                                return 'Extra small';
                            } else {
                                return 'Default';
                            }
                        }
                    }
                }
                , {
                    name: 'icons'
                    , func: 'dropDown'
                    , args: {
                        menu: ['none', 'Left side', 'Right side']
                        , title: 'Icon:'
                        , elClass: 'col-sm-11 col-md-11 col-lg-11'
                        , callback: function() {
                            if (button.firstChild.tagName === 'I') {
                                return 'left side';
                            } else if (button.lastChild.tagName === 'I') {
                                return 'right side';
                            } else {
                                return 'none';
                            }
                        }
                    }
                }
            ], 'col-sm-12 col-md-12 col-lg-12 nopadding btn-image-control hide-setting-btn-html'
        );

        _this._constructModalBody([
                {
                    name: 'inputImage'
                    , func: 'inputImage'
                    , args: {
                    title: 'Image path'
                    , elClass: 'col-sm-12 col-md-10 col-lg-10'
                }
                }
                , {
                    name: 'inputImageRetina'
                    , func: 'inputImage'
                    , args: {
                        title: 'Retina image path'
                        , elClass: 'col-sm-12 col-md-10 col-lg-10 retina'
                    }
                }
                , {
                    name: 'inputText'
                    , func: 'inputText'
                    , args: {
                        title: 'Image Alt'
                        , elClass: 'col-sm-12 col-md-10 col-lg-10'
                    }
                }
            ], 'col-sm-6 col-md-6 col-lg-6 nopadding pre-input-img height-240'
        );

        _this._constructModalBody([
                {
                    name: 'figure'
                    , func: 'figure'
                    , args: {
                    callback: function() {
                        return _this._elements.inputImage;
                    }
                }
                }
            ], 'col-sm-6 col-md-6 col-lg-6 nopadding pre-input-img height-240'
        );

        var image = button.querySelector('img');

        var inputImage = _this._elements.inputImage.querySelector('input');
        var inputRetina = _this._elements.inputImageRetina.querySelector('input');
        var inputRetinaBeforSetNewValue = '';
        var figure = _this._elements.figure.querySelector('img');

        if (inputImage) {
            inputImage.value = image ? image.getAttribute('src') : './images/apple-badge-small.png';
        }
        if (inputRetina) {
            inputRetina.value = image ? image.getAttribute('srcset') : '';
            inputRetinaBeforSetNewValue = inputRetina.value;
        }
        if (figure) {
            figure.src = image ? image.getAttribute('src') : './images/apple-badge-small.png';
        }
        _this._elements.inputText.querySelector('input').value = image ? image.alt : '';

        //var className = '';

        //if (_this._elements.button.querySelector('.choosen .btn')) {
        //    className = _this._elements.button.querySelector('.choosen .btn').className;
        //}

        var className = button.className;

        var triggerIconsChange = false;
        var icons = _this._elements.icons;
        icons.addEventListener('supra.check.select', function (e) {
            triggerIconsChange = true;
        });

        var argsSave = {
            className: className
            , size: _this._elements.size.querySelector('.dropdown button').dataset.value.replace(/_/i, ' ').toLowerCase()
            , inputImage: inputImage.value
            , inputRetina: inputRetina.value
            , icons: _this._elements.icons.querySelector('.dropdown button').dataset.value.replace(/_/ig, ' ').toLowerCase()
            , contentSave: button.innerHTML
            , triggerIC: triggerIconsChange
        };

        //this is need to create new button because modal-footer will be overloaded
        this._footer.innerHTML = '<button type="button" class="supra-btn btn-default" data-dismiss="modal">Cancel</button>';

        var apply = this._getButton('supra-btn btn-primary', 'Apply', function() {

            var className = '';

            if (_this._elements.button.querySelector('.choosen .btn')) {
                className = _this._elements.button.querySelector('.choosen .btn').className;
            }

            var args = {
                className: className
                , size: _this._elements.size.querySelector('.dropdown button').dataset.value.replace(/_/ig, ' ').toLowerCase()
                , inputImage: inputImage.value
                , inputRetina: inputRetina.value
                , icons: _this._elements.icons.querySelector('.dropdown button').dataset.value.replace(/_/ig, ' ').toLowerCase()
                , contentSave: null
                , triggerIC: triggerIconsChange
            };

            _this._applyButtonSettings(_this, button, args, argsSave);

            $(_this._selfDOM).modal('hide');
        });
        this._footer.appendChild(apply);
    }
    /**
     * 
     * @private
     */ 
    , _applyButtonSettings: function(_this, button, args, argsSave) {
        
        if (args.className.search(/btn-image/) !== -1) {
            var img = new Image();
            img.src = args.inputImage;
            img.className = 'spr-option-img-nosettings';
            img.setAttribute('srcset', args.inputRetina);
            button.innerHTML = '';
            button.appendChild(img);
            args.className = 'btn btn-image';
        } else if (button.classList.contains('btn-image')) {
            var span = document.createElement('span');
            span.classList.add('spr-option-textedit');
            span.innerHTML = 'Text';
            button.innerHTML = '';
            button.appendChild(span);
        }
        
        if (args.className.search(/btn-image/) === -1) {
            switch (args.size) {
                case 'large':
                    args.className += ' btn-lg';
                    break;
                case 'small':
                    args.className += ' btn-sm';
                    break;
                case 'extra small':
                    args.className += ' btn-xs';
                    break;
            }
            if (args.contentSave) {
                button.innerHTML = args.contentSave;
            } else {
                if (args.triggerIC) {
                    var oldI = button.querySelector('i');
                    if (oldI) button.removeChild(oldI);
                    var i = document.createElement('i');
                    i.className = 'icon-plus-circle';
                    switch (args.icons) {
                        case 'left side':
                            i.className += ' icon-position-left icon-size-m';
                            var next = button.firstChild;
                            button.insertBefore(i, next);
                            break;
                        case 'right side':
                            i.className += ' icon-position-right icon-size-m';
                            button.appendChild(i);
                            break;
                    }
                }
            }
        }
        
        if (button.classList.contains('btn-block')) args.className += ' btn-block';
        
        button.className = args.className;

        builder.setStep(function () {
            _this._applyButtonSettings(_this, button, argsSave, args);
        });
    }
    /**
     * 
     * @private
     */ 
    , _getModalImageSettings: function(_this) {
        var image = _this._targetObject;
        if (image.nodeName.toLowerCase() !== 'img') {
            image = _this._targetObject.querySelector('img');
        }

        this._title.innerHTML = '<h4>Image settings</h4>';

        this._elements = null;

        _this._constructModalBody([
                {
                    name: 'inputImage'
                    , func: 'inputImage'
                    , args: {
                    title: 'Image path'
                    , elClass: 'col-sm-9 col-md-9 col-lg-9'
                }
                }
                , {
                    name: 'inputImageRetina'
                    , func: 'inputImage'
                    , args: {
                        title: 'Retina image path'
                        , elClass: 'col-sm-9 col-md-9 col-lg-9 retina'
                    }
                }
                , {
                    name: 'inputText'
                    , func: 'inputText'
                    , args: {
                        title: 'Image Alt'
                        , elClass: 'col-sm-9 col-md-9 col-lg-9'
                    }
                }
            ], 'col-sm-6 col-md-6 col-lg-6 nopadding'
        );

        _this._constructModalBody([
                {
                    name: 'figure'
                    , func: 'figure'
                    , args: {
                    callback: function() {
                        return _this._elements.inputImage;
                    }
                }
                }
            ], 'col-sm-6 col-md-6 col-lg-6 nopadding'
        );

        var inputImage = _this._elements.inputImage.querySelector('input');
        var inputRetina = _this._elements.inputImageRetina.querySelector('input');
        var inputRetinaBeforSetNewValue = '';
        var figure = _this._elements.figure.querySelector('img');

        if (inputImage) {
            inputImage.value = image.getAttribute('src');
        }
        if (inputRetina) {
            inputRetina.value = image.getAttribute('srcset');
            inputRetinaBeforSetNewValue = inputRetina.value;
        }
        if (figure) {
            figure.src = image.getAttribute('src');
        }
        _this._elements.inputText.querySelector('input').value = image.alt;

        var argsSave = {
            inputImage: builder.replaceQuotes(_this._elements.inputImage.querySelector('input').value)
            , inputRetina: builder.replaceQuotes(_this._elements.inputImageRetina.querySelector('input').value)
            , inputRetinaBeforSetNewValue: inputRetinaBeforSetNewValue
            , alt: _this._elements.inputText.querySelector('input').value
        };

        //this is need to create new button because modal-footer will be overloaded
        this._footer.innerHTML = '<button type="button" class="supra-btn btn-default" data-dismiss="modal">Cancel</button>';

        var apply = this._getButton('supra-btn btn-primary', 'Apply', function() {
            var args = {
                inputImage: builder.replaceQuotes(_this._elements.inputImage.querySelector('input').value)
                , inputRetina: builder.replaceQuotes(_this._elements.inputImageRetina.querySelector('input').value)
                , inputRetinaBeforSetNewValue: inputRetinaBeforSetNewValue
                , alt: _this._elements.inputText.querySelector('input').value
            };

            _this._applyImageSettings(_this, image, args, argsSave);

            $(_this._selfDOM).modal('hide');
        });
        this._footer.appendChild(apply);
    }
    /**
     * 
     * @private
     */ 
    , _applyImageSettings: function(_this, image, args, argsSave) {

        image.src = args.inputImage;

        if (args.inputRetina !== '' && args.inputRetina !== args.inputRetinaBeforSetNewValue) {
            image.setAttribute('srcset', args.inputRetina);
        } else {
            image.setAttribute('srcset', '');
        }
        image.alt = args.alt;

        //this necessary for gallery
        var owl = controls.findParent(_this._targetObject, ['spr-gallery']);
        if (owl) {
            $(owl).trigger('refresh.owl.carousel');
        }

        builder.setStep(function () {
            _this._applyImageSettings(_this, image, argsSave, args);
        });
    }
    /**
     * 
     * @private
     */ 
    , _getModalGMapSettings: function(_this) {
        this._title.innerHTML = '<h4>Map settings</h4>';

        this._elements = null;

        var script = _this._targetObject.script.innerHTML;
        var funcId = _this._targetObject.map.id.replace(/-/ig, '_');
        
        var contextStart = funcId + '\\([\\n\\s\\w\\/;:\'"#(){}\\[\\]\\|$@!?\\=+,.<>-]*';
        var contextEnd = '[\\n\\s\\w\\/;:\'"#(){}\\[\\]\\|$@!?\\=+,.<>-]*' + funcId + '\\(';

        var patternLatitude = new RegExp(contextStart + 'google\\.maps\\.LatLng\\(([^,]*)' + contextEnd, 'im');
        var currentLatitude = script.match(patternLatitude)[1];

        _this._constructModalBody([
                {
                    name: 'latitude'
                    , func: 'inputText'
                    , args: {
                        title: 'Latitude:'
                        , value: currentLatitude
                        , elClass: 'col-sm-11 col-md-11 col-lg-11'
                    }
                }
            ], 'col-sm-3 col-md-3 col-lg-3 nopadding'
        );

        var patternLongitude = new RegExp(contextStart + 'google\\.maps\\.LatLng\\([^,]*,\\s([^)]*)' + contextEnd, 'im');
        var currentLongitude = script.match(patternLongitude)[1];

        _this._constructModalBody([
                {
                    name: 'longitude'
                    , func: 'inputText'
                    , args: {
                        title: 'Longitude:'
                        , value: currentLongitude
                        , elClass: 'col-sm-11 col-md-11 col-lg-11 '
                    }
                }
            ], 'col-sm-3 col-md-3 col-lg-3 nopadding item-margin-top-0'
        );

        var arrZoom = [];
        for (var i = 1; i < 19; i++) {
            arrZoom.push(i + '');
        }

        var patternZoom = new RegExp(contextStart + 'zoom:\\s*([^,]*)' + contextEnd, 'im');
        var currentZoom = script.match(patternZoom)[1];

        _this._constructModalBody([
                {
                    name: 'zoom'
                    , func: 'dropDown'
                    , args: {
                        menu: arrZoom
                        , title: 'Zoom:'
                        , elClass: 'col-sm-11 col-md-11 col-lg-11'
                        , callback: function() {
                            return currentZoom;
                        }
                    }
                }
            ], 'col-sm-3 col-md-3 col-lg-3 nopadding item-margin-top-0'
        );

        var patternMarker = new RegExp(contextStart + 'var contentString = \'\\s*([^\']*)'+ contextEnd, 'im');
        var currentMarker = script.match(patternMarker)[1];

        _this._constructModalBody([
            {
                name: 'marker'
                , func: 'textArea'
                , args: {
                    title: 'Marker popup content'
                    , elClass: 'col-sm-12 col-md-12 col-lg-12 nopadding-right-10'
                    , value: htmlencode(currentMarker)
                }
            }
            ], 'col-sm-12 col-md-12 col-lg-12 nopadding'
        );

        var ptternStyle = new RegExp('var\\s*([^\\s]*)*\\s*=\\s*\\[','ig');
        var arrStyles = script.match(ptternStyle);
        var menuForStyle = [];
        arrStyles.forEach(function(style) {
            menuForStyle.push(style.match(/var\s([^\s]*)/i)[1]);
        });

        var patternCurStyle = new RegExp(contextStart + 'google\\.maps\\.StyledMapType\\(([^,]*)' + contextEnd, 'im');
        var currentStyle = script.match(patternCurStyle)[1];

        _this._constructModalBody([
                {
                    name: 'style'
                    , func: 'dropDown'
                    , args: {
                        menu: menuForStyle
                        , title: 'Color style:'
                        , elClass: 'col-sm-11 col-md-11 col-lg-11'
                        , callback: function() {
                            return currentStyle;
                        }
                    }
                }
            ], 'col-sm-3 col-md-3 col-lg-3 nopadding'
        );


        var argsSave = {
            latitude: builder.replaceQuotes(_this._elements.latitude.querySelector('input').value)
            , longitude: builder.replaceQuotes(_this._elements.longitude.querySelector('input').value)
            , zoom: _this._elements.zoom.querySelector('.dropdown button').dataset.value.replace(/_/ig, ' ')
            , marker: _this._elements.marker.querySelector('textarea').value
            , style: firstDown(_this._elements.style.querySelector('.dropdown button').dataset.value.replace(/_/ig, ' '))
            , contextStart: contextStart
            , contextEnd: contextEnd
        };

        //this is need to create new button because modal-footer will be overloaded
        this._footer.innerHTML = '<button type="button" class="supra-btn btn-default" data-dismiss="modal">Cancel</button>';

        var apply = this._getButton('supra-btn btn-primary', 'Apply', function() {
            var args = {
                latitude: builder.replaceQuotes(_this._elements.latitude.querySelector('input').value)
                , longitude: builder.replaceQuotes(_this._elements.longitude.querySelector('input').value)
                , zoom: _this._elements.zoom.querySelector('.dropdown button').dataset.value.replace(/_/ig, ' ')
                , marker: _this._elements.marker.querySelector('textarea').value
                , style: firstDown(_this._elements.style.querySelector('.dropdown button').dataset.value.replace(/_/ig, ' '))
                , contextStart: contextStart
                , contextEnd: contextEnd
            };

            _this._applyGMapSettings(_this, args, argsSave);

            $(_this._selfDOM).modal('hide');
        });
        this._footer.appendChild(apply);
    }
    /**
     * 
     * @private
     */ 
    , _applyGMapSettings: function(_this, args, argsSave) {
        
        var li = _this._targetObject.li;
        var scriptLi = li.querySelector('script');
        var script = scriptLi.innerHTML;
        
        var patternLatLng = new RegExp('(google\\.maps\\.LatLng\\()[^,]*,\\s*[^)]*(\\))', 'img');
        var patternContext = new RegExp('(' + args.contextStart + args.contextEnd + ')', 'im');
        var context = script.match(patternContext)[0];
        context = context.replace(patternLatLng, '$1' + args.latitude + ', ' + args.longitude + '$2');
        script = script.replace(patternContext, context);

        var patternZoom = new RegExp('(' + args.contextStart + 'zoom:\\s*)[^,]*(' + args.contextEnd + ')', 'im');
        script = script.replace(patternZoom, '$1' + args.zoom + '$2');

        var patternMarker = new RegExp('(' + args.contextStart + 'var contentString = \'\\s*)[^\']*(\'' + args.contextEnd + ')', 'im');
        script = script.replace(patternMarker, '$1' + htmldecode(args.marker) + '$2');

        var patternCurStyle = new RegExp('(' + args.contextStart + 'google\\.maps\\.StyledMapType\\()[^,]*(' + args.contextEnd + ')', 'im');
        script = script.replace(patternCurStyle, '$1' + args.style + '$2');

        scriptLi.innerHTML = script;

        builder.reloadScript(li);

        builder.setStep(function () {
            _this._applyGMapSettings(_this, argsSave, args);
        });
    }
    /**
     * 
     * @private
     */ 
    , _getModalLinkSettings: function(_this) {
        var Target = _this._targetObject;
        var DOMEelement = _this._targetObject.element;

        var valueHref = DOMEelement.getAttribute('href') || '';
        var valueTarget = DOMEelement.target || '';

        if (Target.editor) {
            var editorAnchor = window.getSelection().anchorNode.parentNode;
            valueHref = editorAnchor.getAttribute('href') || '';
            valueTarget = editorAnchor.target || '';
        }

        this._title.innerHTML = '<h4>Link settings</h4>';

        this._elements = null;

        _this._constructModalBody([
                {
                    name: 'radio'
                    , func: 'radio'
                    , args: {
                    items: ['External link', 'Section link', 'Other page link', 'Video popup']
                    , marginTop: ''
                }
                }
            ], 'col-sm-12 col-md-12 col-lg-12 nopadding radio-control external-link'
        );

        _this._constructModalBody([
                {
                    name: 'inputText'
                    , func: 'inputText'
                    , args: {
                    title: ''
                    , elClass: 'col-sm-12 col-md-12 col-lg-12'
                    , placeholder: 'http://exemple.com'
                    , value: valueHref
                }
                }
            ], 'col-sm-12 col-md-12 col-lg-12 nopadding external-link'
        );

        var menuSections = [];
        var page = builder.getActivePageObject();
        for (var group in page.sections) {
            for(var section in page.sections[group]) {
                menuSections.push(section);
            }
        }

        _this._constructModalBody([
                {
                    name: 'section1'
                    , func: 'dropDown'
                    , args: {
                    menu: menuSections
                    , title: ''
                    , elClass: 'col-sm-12 col-md-12 col-lg-12'
                }
                }
            ], 'col-sm-12 col-md-12 col-lg-12 nopadding section-link'
        );

        var menuPages = [];
        var pages = builder.getPagesArray();
        pages.forEach(function(element) {
            menuPages.push(element.getPageName());
        });

        _this._constructModalBody([
                {
                    name: 'page'
                    , func: 'dropDown'
                    , args: {
                    menu: menuPages
                    , title: ''
                    , elClass: 'col-sm-12 col-md-12 col-lg-12'
                    , callback: function() {
                        return builder.getActivePageObject().getPageName();
                    }
                }
                }
            ], 'col-sm-6 col-md-6 col-lg-6 nopadding-right-10 other-page-link'
        );

        _this._constructModalBody([
                {
                    name: 'section2'
                    , func: 'dropDown'
                    , args: {
                    menu: menuSections
                    , title: ''
                    , elClass: 'col-sm-12 col-md-12 col-lg-12'
                }
                }
            ], 'col-sm-6 col-md-6 col-lg-6 nopadding other-page-link'
        );

        _this._constructModalBody([
                {
                    name: 'target'
                    , func: 'checkbox'
                    , args: {
                    name: 'Open in new tab'
                    , checked: valueTarget === '_blank' ? true : false
                }
                }
            ], 'col-sm-12 col-md-12 col-lg-12 nopadding field-checkbox external-link'
        );

        _this._constructModalBody([
            {
                name: 'videoLink'
                , func: 'inputText'
                , args: {
                    title: 'Iframe source URL'
                    , elClass: 'col-sm-12 col-md-12 col-lg-12'
                    , placeholder: 'https://vimeo.com/123395658'
                    , value: valueHref
                }
            }
            , {
                name: 'description'
                , func: 'description'
                , args: {
                    value: 'Examples:<br>'
                            + 'Vimeo: https://vimeo.com/123395658<br>'
                            + 'Youtube: https://www.youtube.com/watch?v=JLhbTGzE6MA'
                }
            }
            ], 'col-sm-12 col-md-12 col-lg-12 nopadding video-popup'
        );

        var radio = _this._elements.radio.querySelectorAll('.radio-inline input');
        Array.prototype.forEach.call(radio, function(item) {
            item.addEventListener('change', function(e) {
                e.preventDefault();
                var radioControl = controls.findParent(this, ['radio-control']);
                var checkbox = controls.findParent(_this._elements.target, ['field-checkbox']);
                radioControl.className = radioControl.className.replace(/(external-link|section-link|other-page-link|video-popup)/i, '');
                checkbox.className = checkbox.className.replace(/(external-link|section-link|other-page-link|video-popup)/i, '');
                radioControl.classList.add(this.value);
                if (this.value === 'external-link' || this.value === 'other-page-link') {
                    checkbox.classList.add(this.value);
                } else {
                    _this._elements.target.querySelector('input').checked = false;
                }
            });
        });

        var patternHref = new RegExp('([\\w._-]*)?\\/?#?([\\w_-]*)?', 'i');
        var patternHrefVideo = new RegExp('(vimeo\\.com|youtube\\.com)', 'i');
        //var attrHref = DOMEelement.getAttribute('href') || '';
        var attrHref = valueHref;
        var parseHref = attrHref.match(patternHref);
        var parseHrefVideo = attrHref.match(patternHrefVideo);

        if (!parseHrefVideo && parseHref && parseHref[1]) {
            var pagesNames = builder.getPagesNamesArray();
            var triggerPage = false;
            pagesNames.forEach(function(name) {
                if (parseHref[1].replace(/.html/, '') === name) triggerPage = true;
            });
            if (triggerPage) {
                radio[2].checked = true;
                _this._elements.page.querySelector('.dropdown button').dataset.value = parseHref[1].replace(/.html/, '');
                _this._elements.page.querySelector('.dropdown button span').innerHTML = firstUp(parseHref[1].replace(/.html/, ''));
                _this._elements.section2.querySelector('.dropdown button').dataset.value = parseHref[2];
                _this._elements.section2.querySelector('.dropdown button span').innerHTML = firstUp(parseHref[2]);
                _this._setTypeLink(radio[2], true);
            }
        } else if (!parseHrefVideo && parseHref && parseHref[2]) {
            radio[1].checked = true;
            _this._elements.section1.querySelector('.dropdown button').dataset.value = parseHref[2];
            _this._elements.section1.querySelector('.dropdown button span').innerHTML = firstUp(parseHref[2]);
            _this._setTypeLink(radio[1]);
            _this._elements.target.querySelector('input').checked = false;
        } else if (parseHrefVideo) {
            radio[3].checked = true;
            _this._setTypeLink(radio[3]);
        }

        if (DOMEelement.target === '_blank') {
            _this._elements.target.querySelector('input').checked = true;
        }

        _this._elements.page.addEventListener('supra.check.select', function(e) {
            pages.forEach(function(page) {
                if (page.getPageName() === e.detail.toLowerCase()) {
                    var sectionUl = _this._elements.section2.querySelector('ul');
                    sectionUl.innerHTML = '';
                    var valueDropDown = false;
                    for (var group in page.sections) {
                        for(var section in page.sections[group]) {
                            if (!valueDropDown) {
                                valueDropDown = true;
                                var button = _this._elements.section2.querySelector('button');
                                var val = section;
                                button.dataset.value = replaceSpace(val.toLowerCase());
                                button.querySelector('span').innerHTML = val;
                            }
                            var li = document.createElement('li');
                            var a = document.createElement('a');
                            li.appendChild(a);
                            a.innerHTML = section;
                            sectionUl.appendChild(li);
                        }
                    }

                    _this._addEventListToDropdown(_this._elements.section2);
                }
            });
        });

        var argsSave = {
            link: DOMEelement.href
            , targetLink: DOMEelement.target || '_self'
        };

        if (_this._targetObject.mode === 'static') {
            this._footer.innerHTML = '<button type="button" class="supra-btn btn-default" data-dismiss="modal">Cancel</button>';
        } else {
            var unlink = this._getButton('supra-btn btn-danger', 'Unlink', function () {
                if (DOMEelement.nodeName === 'A') {
                    _this._removeLink(_this, DOMEelement);
                    if (Target.button.classList.contains('active'))
                        Target.button.classList.remove('active');
                } else if (Target.editor && Target.button.classList.contains('active')) {
                    Target.editor.removeLink(Target.button);
                }

                $(_this._selfDOM).modal('hide');
            });

            this._footer.appendChild(unlink);
        }

        var apply = this._getButton('supra-btn btn-primary', 'Apply', function() {

            var radio = _this._elements.radio.querySelector('.radio-inline input:checked').value;

            var link = builder.replaceQuotes(_this._elements.inputText.querySelector('input').value);
            var targetLink = _this._elements.target.querySelector('input').checked ? '_blank' : '_self';
            if (radio === 'section-link') {
                link = '#' + _this._elements.section1.querySelector('.dropdown button').dataset.value;
            } else if (radio === 'other-page-link') {
                var page = _this._elements.page.querySelector('.dropdown button').dataset.value + '.html';
                var section = _this._elements.section2.querySelector('.dropdown button').dataset.value;
                link = page + '#' + section;
            } else if (radio === 'video-popup') {
                link = builder.replaceQuotes(_this._elements.videoLink.querySelector('input').value);
            }

            if (Target.editor) {
                var anchor = null;
                if (Target.button.classList.contains('active') && editorAnchor.nodeName !== "A") {
                    Target.editor.removeLink(Target.button);
                } else if (editorAnchor.nodeName === "A") {
                    Target.editor.removeLink(Target.button);
                    anchor = Target.editor.setLink(Target.button, link, targetLink);
                } else {
                    anchor = Target.editor.setLink(Target.button, link, targetLink);
                }

                if (anchor && radio === 'video-popup') {
                    builder.applyMagnificPopup(anchor);
                }

            } else {

                if (DOMEelement.nodeName === 'A') {
                    var args = {
                        link: link
                        , targetLink: targetLink
                    };

                    _this._changeLink(_this, DOMEelement, args, argsSave);
                } else {
                    _this._setLink(_this, DOMEelement, link, targetLink);
                }

                if (!Target.button.classList.contains('active'))
                    Target.button.classList.add('active');

                if (radio === 'video-popup') {
                    builder.applyMagnificPopup(DOMEelement);
                }
            }

            //this necessary for gallery
            var owl = controls.findParent(DOMEelement, ['spr-gallery']);
            if (owl) {
                $(owl).trigger('refresh.owl.carousel');
            }

            //this necessary for video
            if (radio !== 'video-popup') {
                if (DOMEelement.classList.contains('single-iframe-popup')) DOMEelement.classList.remove('single-iframe-popup');
            }

            $(_this._selfDOM).modal('hide');
        });
        this._footer.appendChild(apply);
    }
    /**
     * 
     * @private
     */ 
    , _changeLink: function(_this, DOMEelement, args, argsSave) {
        DOMEelement.href = args.link;
        DOMEelement.target = args.targetLink;
        DOMEelement.classList.add('smooth');

        $(DOMEelement).smoothScroll({speed: 800});

        builder.setStep(function () {
            _this._changeLink(_this, DOMEelement, argsSave, args);
        });
    }
    /**
     * 
     * @private
     */ 
    , _setLink: function(_this, DOMEelement, link, targetLink) {
        if (DOMEelement.tagName === 'A') {
            var args = {
                link: link
                , targetLink: targetLink
            };
            var argsSave = {
                link: DOMEelement.getAttribute('href')
                , targetLink: DOMEelement.target
            };
            _this._changeLink(_this, DOMEelement, args, argsSave);
        } else {
            var a = document.createElement('a');
            a.href = link;
            a.target = targetLink;
            a.classList.add('smooth');
            DOMEelement.parentElement.insertBefore(a, DOMEelement);
            a.wrap(DOMEelement);
            if (DOMEelement.classList.contains('slide-photo')) {
                a.classList.add('slide-photo');
                a.style.position = 'static';
                DOMEelement.classList.remove('slide-photo');
                DOMEelement.removeAttribute('style');
            }
            controls.rebuildControl(a.parentElement);

            $(a).smoothScroll({speed: 800});

            builder.setStep(function() {
                // anchor element need to be inner element of 'a'
                // , because 'a' element will be removed and not have children elements
                _this._removeLink(_this, DOMEelement.parentElement);
            });
        }

    }
    /**
     * 
     * @private
     */ 
    , _setTypeLink: function(radio, chbx) {
        var _this = this;
        var radioControl = controls.findParent(radio, ['radio-control']);
        var checkbox = controls.findParent(_this._elements.target, ['field-checkbox']);
        radioControl.className = radioControl.className.replace(/(external-link|section-link|other-page-link|video-popup)/i, '');
        checkbox.className = checkbox.className.replace(/(external-link|section-link|other-page-link|video-popup)/i, '');
        radioControl.classList.add(radio.value);
        if (chbx) checkbox.classList.add(radio.value);
    }
    /**
     * 
     * @private
     */ 
    , _removeLink: function(_this, a) {
        var DOMElement = a.children[0];
        var gallery = controls.findParent(a, ['spr-gallery']);
        var link = a.getAttribute('href');
        var target = a.target;
        if (DOMElement && !gallery && DOMElement.nodeName !== 'SPAN') {
            a.unWrapOne();
        } else {
            DOMElement = a;
            a.href = '/';
            a.target = '_self';
        }

        builder.setStep(function() {
            _this._setLink(_this, DOMElement, link, target);
        });
    }
    /**
     * 
     * @private
     */ 
    , _getModalVideoLinkSettings: function(_this) {
        var DOMEelement = _this._targetObject.element;

        var valueSrc = DOMEelement.src || '';

        this._title.innerHTML = '<h4>Link settings</h4>';

        this._elements = null;

        _this._constructModalBody([
                {
                    name: 'videoLink'
                    , func: 'inputText'
                    , args: {
                    title: 'Iframe source URL'
                    , elClass: 'col-sm-12 col-md-12 col-lg-12'
                    , placeholder: 'https://vimeo.com/123395658'
                    , value: valueSrc
                }
                }
                , {
                    name: 'description'
                    , func: 'description'
                    , args: {
                        value: 'Examples:<br>'
                        + 'Vimeo: https://vimeo.com/123395658<br>'
                        + 'Youtube: https://www.youtube.com/watch?v=JLhbTGzE6MA'
                    }
                }
            ], 'col-sm-12 col-md-12 col-lg-12 nopadding'
        );

        this._footer.innerHTML = '<button type="button" class="supra-btn btn-default" data-dismiss="modal">Cancel</button>';

        var argsSave = {
            link: builder.replaceQuotes(_this._elements.videoLink.querySelector('input').value)
        };

        var apply = this._getButton('supra-btn btn-primary', 'Apply', function() {

            var args = {
                link: builder.replaceQuotes(_this._elements.videoLink.querySelector('input').value)
            };

            if (args.link.search(/player\.vimeo\.com|embed/i) === -1) {
                _this._applyVideoLinkSettings(_this, DOMEelement, args, argsSave, false);
            }

            $(_this._selfDOM).modal('hide');
        });
        this._footer.appendChild(apply);
    }
    /**
     * 
     * @private
     */ 
    , _applyVideoLinkSettings: function(_this, DOMEelement, args, argsSave, saved) {

        if (saved) {
            DOMEelement.src = args.link;
            saved = false;
        } else {
            var id = _this._getVideoId;
            var videoDomain = _this._getVideoDomain;

            DOMEelement.src = videoDomain(args.link) + id(args.link);
            saved = true;
        }

        builder.setStep(function() {
            _this._applyVideoLinkSettings(_this, DOMEelement, argsSave, args, saved);
        });
    }
    /**
     * 
     * @private
     */ 
    , _getVideoId: function (url) {
        var m = url.match(/(vimeo\.com.*\/([0-9]*)|youtube\.com\/watch\?v=(.*))/);
        if (m) {
            if (m[2] !== undefined) {
                return m[2];
            }
            return m[3];
        }
        return null;
    }
    /**
     * 
     * @private
     */ 
    , _getVideoDomain: function(url) {
        var m = url.match(/(vimeo\.com)|(youtube\.com)/);
        if (m) {
            if (m[1] !== undefined) {
                return 'https://player.vimeo.com/video/';
            }
            return 'https://www.youtube.com/embed/';
        }
    }
    /**
     * 
     * 
     */ 
    , _getModalPageSettings: function(_this) {
        var page = _this._targetObject.page;
        _this._title.innerHTML = '<h4>Page settings</h4>';

        _this._constructModalBody([
                {
                    name: 'buttonGroup'
                    , func: 'pageSettinsButton'
                }
            ], 'col-sm-12 col-md-12 col-lg-12 nopadding btn-page-control general'
        );

        _this._constructModalBody([
                {
                    name: 'name'
                    , func: 'inputText'
                    , args: {
                    title: 'Name'
                    , elClass: 'col-sm-12 col-md-12 col-lg-12'
                    , placeholder: 'Index'
                    , value: page.getPageName()
                }
                }
                , {
                    name: 'title'
                    , func: 'inputText'
                    , args: {
                        title: 'Title'
                        , elClass: 'col-sm-12 col-md-12 col-lg-12'
                        , placeholder: 'Title'
                        , value: page.getPageTitle()
                    }
                }
                , {
                    name: 'skin'
                    , func: 'switch'
                    , args: {
                        title: 'Default Dark skin'
                        , type: 'gray'
                        , checked: page.getDOMSelf().classList.contains('dark-page')
                        , callback: function(sw) {
                            sw.querySelector('.switch').addEventListener('click', function(e) {
                                e.preventDefault();
                                if (this.classList.contains('switch-on')) {
                                    this.classList.remove('switch-on');
                                    this.classList.add('switch-off');
                                    this.querySelector('input').removeAttribute('checked');
                                } else {
                                    this.classList.remove('switch-off');
                                    this.classList.add('switch-on');
                                    this.querySelector('input').setAttribute('checked' , '');
                                }
                            });
                        }
                    }
                }
            ], 'col-sm-12 col-md-12 col-lg-12 nopadding general'
        );

        _this._constructModalBody([
                {
                    name: 'textAreaDesc'
                    , func: 'textArea'
                    , args: {
                    title: 'Meta Description'
                    , elClass: 'col-sm-6 col-md-6 col-lg-6 nopadding-right-10'
                    , value: page.getMetaDes()
                }
                }
                , {
                    name: 'textAreaKeyw'
                    , func: 'textArea'
                    , args: {
                        title: 'Meta Keywords'
                        , elClass: 'col-sm-6 col-md-6 col-lg-6 nopadding-left-10'
                        , value: page.getMetaKey()
                    }
                }
                , {
                    name: 'textAreaJs'
                    , func: 'textArea'
                    , args: {
                        title: 'Included JavaScript (Google Analitics e.t.c.)'
                        , elClass: 'col-sm-12 col-md-12 col-lg-12 nopadding'
                        , value: page.getJs()
                    }
                }
            ], 'col-sm-12 col-md-12 col-lg-12 nopadding seo'
        );

        _this._constructModalBody([
                {
                    name: 'preloaderType'
                    , func: 'preloaderType'
                    , args: {
                    title: 'Preloader type'
                    , elClass: 'col-sm-12 col-md-12 col-lg-12'
                    , html: [
                        'None'
                        , '<i class="icon-picture"></i><span>Image</span>'
                        , '<div class="clock"><div class="arrow_sec"></div><div class="arrow_min"></div></div>'
                        , '<div class="circles"><div class="bounce1"></div>'
                            + '<div class="bounce2"></div><div class="bounce3"></div></div>'
                    ]
                    , dataName: ['none', 'img', 'anim_clock', 'anim_jp']
                    , active: page.preloader ? page.preloader.name : 'none'
                }
                }
                , {
                    name: 'inputImage'
                    , func: 'inputImage'
                    , args: {
                        title: 'Image path'
                        , elClass: 'col-sm-12 col-md-12 col-lg-12 pre-input-img'
                    }
                }
                , {
                    name: 'inputImageRetina'
                    , func: 'inputImage'
                    , args: {
                        title: 'Retina image path'
                        , elClass: 'col-sm-12 col-md-12 col-lg-12 pre-input-img retina'
                    }
                }
            ], 'col-sm-12 col-md-12 col-lg-12 nopadding s-preloader'
        );

        if (page.preloader && page.preloader.name === 'img') {
            var src = page.preloader.html.match(/src="([^"]*)"/i)[1];
            var srcset = page.preloader.html.match(/srcset="([^"]*)"/i)[1];
            this._elements.inputImage.querySelector('input').value = src;
            this._elements.inputImageRetina.querySelector('input').value = srcset;
            this._body.classList.add('show-input-img');
        }


        var preloader = _this._elements.preloaderType.querySelector('.active');

        var argsSave = {
            pageName: _this._elements.name.querySelector('input').value
            , pageTitle: _this._elements.title.querySelector('input').value
            , skin: _this._elements.skin.querySelector('input').checked
            , description: _this._elements.textAreaDesc.querySelector('textarea').value
            , keywords: _this._elements.textAreaKeyw.querySelector('textarea').value
            , js: _this._elements.textAreaJs.querySelector('textarea').value
            , preloader: preloader
            , imgPath: _this._elements.inputImage.querySelector('input').value
            , retinaPath: _this._elements.inputImageRetina.querySelector('input').value
            , preloaderHtml: preloader ? preloader.innerHTML : ''
        };

        //this is need to create new button because modal-footer will be overloaded
        this._footer.innerHTML = '<button type="button" class="supra-btn btn-default" data-dismiss="modal">Cancel</button>';

        var apply = this._getButton('supra-btn btn-primary', 'Apply', function() {

            preloader = _this._elements.preloaderType.querySelector('.active');

            var args = {
                pageName: _this._elements.name.querySelector('input').value
                , pageTitle: _this._elements.title.querySelector('input').value
                , skin: _this._elements.skin.querySelector('input').checked
                , description: _this._elements.textAreaDesc.querySelector('textarea').value
                , keywords: _this._elements.textAreaKeyw.querySelector('textarea').value
                , js: _this._elements.textAreaJs.querySelector('textarea').value
                , preloader: preloader
                , imgPath: _this._elements.inputImage.querySelector('input').value
                , retinaPath: _this._elements.inputImageRetina.querySelector('input').value
            };

            _this._applyPageSettings(_this, page, args, argsSave);

            $(_this._selfDOM).modal('hide');
        });
        this._footer.appendChild(apply);
    }
    /**
     * 
     * @private
     */  
    , _applyPageSettings: function(_this, page, args, argsSave) {
        if (args.pageName.charAt(0).search(/[0-9]/) !== -1) {
            args.pageName = 'p-' + args.pageName;
        }
        page.setPageName(args.pageName.toLowerCase());
        builder.setPageItemsName(args.pageName.toLowerCase(), _this._targetObject.pageItem);
        page.setPageTitle(args.pageTitle);
        _this._skinCheck(_this, args.skin, page.getDOMSelf(), 'light-page', 'dark-page');

        page.setMetaDes(args.description);
        page.setMetaKey(args.keywords);
        page.setJs(args.js);

        if (args.preloader) {
            switch (args.preloader.dataset.value) {
                case 'img':
                    var img = '<img src="'
                        + builder.replaceQuotes(args.imgPath) + '" srcset="'
                        + builder.replaceQuotes(args.retinaPath) + '" alt="preloader image"/>';
                    page.preloader = {
                        name: args.preloader.dataset.value
                        , html: '<div id="preloader">' + img + '</div>'
                    };
                    break;
                case 'anim_clock':
                    page.preloader = {
                        name: args.preloader.dataset.value
                        , html: '<div id="preloader"><div class="clock"><div class="arrow_sec"></div><div class="arrow_min"></div></div></div>'
                    };
                    break;
                case 'anim_jp':
                    page.preloader = {
                        name: args.preloader.dataset.value
                        , html: '<div id="preloader"><div class="circles"><div class="bounce1"></div>'
                        + '<div class="bounce2"></div><div class="bounce3"></div></div></div>'
                    };
                    break;
                case 'none':
                default:
                    page.preloader = {
                        name: args.preloader.dataset.value
                        , html: ''
                    };
                    break;
            }
        } else {
            page.preloader = null;
        }

        builder.setStep(function() {
            _this._applyPageSettings(_this, page, argsSave, args);
        });
    }
    /**
     * 
     * @private
     */ 
    , _getModalFormSettings: function (_this) {
        var form = _this._targetObject;
        var li = controls.findParent(form, ['section-item']);
        var section = li.children[0];
        var subject = builder.forms[section.id].settings ? builder.forms[section.id].settings.subject : '';
        var address = builder.forms[section.id].settings ? builder.forms[section.id].settings.email : '';

        this._title.innerHTML = '<h4>Contact form settings</h4>';

        this._elements = null;

        _this._constructModalBody([
                {
                    name: 'subject'
                    , func: 'inputText'
                    , args: {
                    title: 'Subject'
                    , elClass: 'col-sm-12 col-md-12 col-lg-12'
                    , value: subject
                    , placeholder: 'Enter email subject'
                }
                }
                , {
                    name: 'eMail'
                    , func: 'inputText'
                    , args: {
                        title: 'Email address'
                        , elClass: 'col-sm-12 col-md-12 col-lg-12'
                        , value: address
                        , placeholder: 'Enter email address'
                    }
                }
            ], 'col-sm-12 col-md-12 col-lg-12 nopadding'
        );

        _this._constructModalBody([
                {
                    name: 'radio'
                    , func: 'radio'
                    , args: {
                        title: 'Confirm method'
                        , items: ['None', 'Popups', 'Redirect']
                        , marginTop: ''
                    }
                }
            ], 'col-sm-12 col-md-12 col-lg-12 nopadding radio-control none'
        );

        _this._constructModalBody([
                {
                    name: 'description'
                    , func: 'description'
                    , args: {
                        value: 'If you use confirmation type with popups, after successful sending'
                        + ' of message your user will see popups with confirm information (on error popup if'
                        + ' somesing do wrong). Customize popups you can in elements mode.'
                    }
                }
            ], 'col-sm-12 col-md-12 col-lg-12 nopadding item-margin-top-0 popups'
        );

        _this._constructModalBody([
                {
                    name: 'redirectLink'
                    , func: 'inputText'
                    , args: {
                        title: ''
                        , elClass: 'col-sm-12 col-md-12 col-lg-12'
                        , placeholder: 'http://URL.com'
                        , value: builder.forms[section.id].rLink ? builder.forms[section.id].rLink : ''
                    }
                }
            ], 'col-sm-12 col-md-12 col-lg-12 nopadding item-margin-top-0 redirect'
        );

        _this._constructModalBody([
                {
                    name: 'target'
                    , func: 'checkbox'
                    , args: {
                    name: 'Open in new tab'
                    , checked: builder.forms[section.id].target === '_blank' ? true : false
                }
                }
            ], 'col-sm-12 col-md-12 col-lg-12 nopadding field-checkbox item-margin-top-0 redirect'
        );

        var radio = _this._elements.radio.querySelectorAll('.radio-inline input');
        Array.prototype.forEach.call(radio, function(item) {
            item.addEventListener('change', function(e) {
                e.preventDefault();
                _this._changeConfirmMode(this);
            });
        });

        switch (builder.forms[section.id].mode) {
            case 'popups':
                radio[1].checked = true;
                _this._changeConfirmMode(radio[1]);
                break;
            case 'redirect':
                radio[2].checked = true;
                _this._changeConfirmMode(radio[2]);
                break;
        }

        if (builder.forms[section.id].target === '_blank') {
            _this._elements.target.querySelector('input').checked = true;
        }

        var argsSave = {
            subject: _this._elements.subject.querySelector('input').value
            , address: _this._elements.eMail.querySelector('input').value
            , radio: _this._elements.radio.querySelector('.radio-inline input:checked').value
            , rLink: _this._elements.redirectLink.querySelector('input').value
            , target: _this._elements.target.querySelector('input').checked
        };

        //this is need to create new button because modal-footer will be overloaded
        this._footer.innerHTML = '<button type="button" class="supra-btn btn-default" data-dismiss="modal">Cancel</button>';

        var apply = this._getButton('supra-btn btn-primary', 'Apply', function() {

            var args = {
                subject: _this._elements.subject.querySelector('input').value
                , address: _this._elements.eMail.querySelector('input').value
                , radio: _this._elements.radio.querySelector('.radio-inline input:checked').value
                , rLink: _this._elements.redirectLink.querySelector('input').value
                , target: _this._elements.target.querySelector('input').checked
            };

            _this._applyFormSettings(_this, subject, address, args, argsSave);

            $(_this._selfDOM).modal('hide');
        });
        this._footer.appendChild(apply);
    }
    /**
     * 
     * @private
     */ 
    , _changeConfirmMode: function(radioInput) {
        var _this = this;
        var radioControl = controls.findParent(radioInput, ['radio-control']);
        var checkbox = controls.findParent(_this._elements.target, ['field-checkbox']);
        radioControl.className = radioControl.className.replace(/(none|popups|redirect)/i, '');
        checkbox.className = checkbox.className.replace(/(none|popups|redirect)/i, '');
        radioControl.classList.add(radioInput.value);
        if (radioInput.value === 'redirect') {
            checkbox.classList.add(radioInput.value);
        } else {
            _this._elements.target.querySelector('input').checked = false;
        }
    }
    /**
     * 
     * @private
     */ 
    , _applyFormSettings: function(_this, subject, address, args, argsSave) {
        var form = _this._targetObject;
        var li = controls.findParent(form, ['section-item']);
        var section = li.children[0];
        var script = li.querySelector('script');

        builder.forms[section.id].settings = {
            subject: args.subject
            , email: args.address
            , type: 'contact'
            , id: form.id
        };

        var patternSuccess = new RegExp('success:\\s*function\\s*\\(.*\\)\\s*{[^}]*}', 'im');
        var patternError = new RegExp('error:\\s*function\\s*\\(.*\\)\\s*{[^}]*}', 'im');
        var patternOpenWindow = new RegExp('(\\$\\(\'#' + section.id + '-form\'\\)\\.submit\\(function\\s*\\(\\)\\s*{)', 'i');
        var patternErrorValid = new RegExp('(\\/\\/if data was invalidated)', 'im');
        var patternLClick = new RegExp('\\$\\(\'#' + section.id + '-form \\[type=submit\\]\'\\)\\.on\\(\'click\'[\\n\\s\\w\\/;:\'"#(){}\\[\\]\\|$@!?\\=+,.-]*\'_blank\'\\);\\n}\\);\\n', 'im');
        var patternCloseWindow = new RegExp('\\n\\t\\twindow\\.wBlank\\.close\\(\\);\\n', 'img');
        
        var successCode = 'success: function () {'
            + '\n\t$(\'#' + section.id + '-form\').find(\'[type=submit]\').button(\'complete\');'
            + '\n}';
        var errorCode = 'error: function () {'
            + '\n\t$(\'#' + section.id + '-form\').find(\'[type=submit]\').button(\'reset\');'
            + '\n}';

        var target = args.target ? '_blank' : '_self';

        var lineRedirect = '\t\twindow.open(\'' + args.rLink + '\',\'_self\');\n';
        var closeWindowCode = '';

        if (target === '_blank') {
            lineRedirect = '\t\twindow.wBlank.location = \'' + args.rLink + '\';\n';
            closeWindowCode = '\n\t\twindow.wBlank.close();\n';
            if (script.innerHTML.search(patternLClick) === -1) {
                var openWindowCode = '$(\'#' + section.id + '-form [type=submit]\').on(\'click\', function() {\n'
                    + '\t\twindow.wBlank = window.open(\'\',\'_blank\');\n'
                    + '});\n';
                script.innerHTML = script.innerHTML.replace(patternOpenWindow, openWindowCode + '$1');
                script.innerHTML = script.innerHTML.replace(patternErrorValid, '$1' + closeWindowCode);
            }
        } else {
            script.innerHTML = script.innerHTML.replace(patternLClick, '');
            script.innerHTML = script.innerHTML.replace(patternCloseWindow, '');
        }

        switch (args.radio) {
            case 'popups':
                successCode = 'success: function () {\n'
                    + '\t\t$(\'#' + section.id + '-form\').find(\'[type=submit]\').button(\'complete\');\n'
                    + '\t\t//Use modal popups to display messages\n'
                    + '\t\t$(document).find(\'#' + section.id + '-success\').modal(\'show\');\n'
                    + '\t}';
                errorCode = 'error: function () {\n'
                    + '\t\t$(\'#' + section.id + '-form\').find(\'[type=submit]\').button(\'reset\');\n'
                    + '\t\t//Use modal popups to display messages\n'
                    + '\t\t$(document).find(\'#' + section.id + '-error\').modal(\'show\');\n'
                    + '\t}';
                break;

            case 'redirect':
                successCode = 'success: function () {\n'
                    + '\t\t$(\'#' + section.id + '-form\').find(\'[type=submit]\').button(\'complete\');\n'
                    + '\t\t//Use modal popups to display messages\n'
                    + lineRedirect
                    + '\t}';
                errorCode = 'error: function () {\n'
                    + closeWindowCode
                    + '\t}';
                builder.forms[section.id].rLink = args.rLink;
                builder.forms[section.id].target = target;
                break;
        }

        builder.forms[section.id].mode = args.radio;

        script.innerHTML = script.innerHTML.replace(patternSuccess, successCode);
        script.innerHTML = script.innerHTML.replace(patternError, errorCode);

        builder.setStep(function() {
            _this._applyFormSettings(_this, subject, address, argsSave, args);
        });
    }
    /**
     * 
     * @private
     */ 
    , _getModalSubscribeFormSettings: function (_this) {
        var form = _this._targetObject;
        var li = controls.findParent(form, ['section-item']);
        var section = li.children[0];
        var apiKey = builder.forms[section.id].settings ? builder.forms[section.id].settings.apiKey : '';
        var listId = builder.forms[section.id].settings ? builder.forms[section.id].settings.listId : '';

        this._title.innerHTML = '<h4>Subscribe form settings</h4>';

        this._elements = null;

        _this._constructModalBody([
                {
                    name: 'apiKey'
                    , func: 'inputText'
                    , args: {
                    title: 'Mailchimp API Key'
                    , elClass: 'col-sm-12 col-md-12 col-lg-12'
                    , value: apiKey
                    , placeholder: 'Enter your mailchimp API Key'
                }
                }
                , {
                    name: 'listId'
                    , func: 'inputText'
                    , args: {
                        title: 'Mailchimp List ID'
                        , elClass: 'col-sm-12 col-md-12 col-lg-12'
                        , value: listId
                        , placeholder: 'Enter your mailchimp List ID'
                    }
                }
            ], 'col-sm-12 col-md-12 col-lg-12 nopadding'
        );

        var argsSave = {
            apiKey: _this._elements.apiKey.querySelector('input').value
            , listId: _this._elements.listId.querySelector('input').value
        };

        //this is need to create new button because modal-footer will be overloaded
        this._footer.innerHTML = '<button type="button" class="supra-btn btn-default" data-dismiss="modal">Cancel</button>';

        var apply = this._getButton('supra-btn btn-primary', 'Apply', function() {

            var args = {
                apiKey: _this._elements.apiKey.querySelector('input').value
                , listId: _this._elements.listId.querySelector('input').value
            };

            _this._applySubscribeFormSettings(_this, args, argsSave);

            $(_this._selfDOM).modal('hide');
        });
        this._footer.appendChild(apply);
    }
    /**
     * 
     * @private
     */ 
    , _applySubscribeFormSettings: function(_this, args, argsSave) {
        var form = _this._targetObject;
        var li = controls.findParent(form, ['section-item']);
        var section = li.children[0];
        var script = li.querySelector('script');

        builder.forms[section.id].settings = {
            apiKey: args.apiKey
            , listId: args.listId
            , type: 'subscribe'
            , id: form.id
        };

        var patternSuccess = new RegExp('#modalSubscribeSuccess', 'img');
        var patternError = new RegExp('#modalSubscribeError', 'img');

        var successCode = '#' + section.id + '-success';
        var errorCode = '#' + section.id + '-error';

        script.innerHTML = script.innerHTML.replace(patternSuccess, successCode);
        script.innerHTML = script.innerHTML.replace(patternError, errorCode);

        builder.setStep(function() {
            _this._applySubscribeFormSettings(_this, argsSave, args);
        });
    }
    /**
     * 
     * @private
     */ 
    , _getModalDivBg: function (_this) {
        var divBackground = this._targetObject;
        this._title.innerHTML = '<h4>Background settings</h4>';

        this._elements = null;

        var li = controls.findParent(divBackground, ['section-item']);
        var className = divBackground.className.match(/half-container-\w*/)[0];
        var section = li.children[0];
        var style = li.querySelector('style').innerHTML;

        var patternStyleSize = new RegExp(section.id + '\\s*\\.' + className + '\\s*{[ \\n\\t\\ra-z0-9:()\'\\/.;_@-]*background-size:\\s*([^;]*);', 'im');
        var bgOptions = style.match(patternStyleSize);
        var patternStyleRepeat = new RegExp(section.id + '\\s*\\.' + className + '\\s*{[ \\n\\t\\ra-z0-9:()\'\\/.;_@-]*background-repeat:\\s*([^;]*);', 'im');
        var repeat = style.match(patternStyleRepeat);

        _this._constructModalBody([
                {
                    name: 'inputImage'
                    , func: 'inputImage'
                    , args: {
                        title: 'Background path'
                        , elClass: 'col-sm-9 col-md-9 col-lg-9'
                    }
                }
                , {
                    name: 'BgStyle'

                    , func: 'dropDown'
                    , args: {
                        menu: ['cover', 'auto', 'contain', 'repeat']
                        , title: 'Background style:'
                        , elClass: 'col-sm-9 col-md-9 col-lg-9'
                        , callback: function() {
                            if (repeat && repeat[1] === 'repeat') {
                                return repeat[1];
                            }
                            return  bgOptions ? bgOptions[1] : 'Auto';
                        }
                    }
                }
            ], 'col-sm-6 col-md-6 col-lg-6 nopadding'
        );

        var patternOpacity = new RegExp(section.id + '\\s*\\.' + className + '\\s*{[ \\n\\t\\ra-z0-9:()\'\\/.;_@-]*opacity:[\\s]*([^;]*)', 'im');
        var opacity = style.match(patternOpacity);

        _this._constructModalBody([
                {
                    name: 'figure'
                    , func: 'figure'
                    , args: {
                    callback: function() {
                        return _this._elements.inputImage;
                    }
                    , section: divBackground
                    , sizeAuto: !bgOptions || (bgOptions && bgOptions[1] === 'auto')
                }
                }
                , {
                    name: 'inputRange'
                    , func: 'inputRange'
                    , args: {
                        opacity: function() {
                            return opacity;
                        }
                    }
                }

            ], 'col-sm-6 col-md-6 col-lg-6 nopadding preview-bg'
        );

        var img = _this._elements.figure.querySelector('img');
        var divImg = _this._elements.figure.querySelector('.img');
        divImg.style.display = 'block';

        $(_this._selfDOM).on('shown.bs.modal', function() {
            var widthDivBg = divBackground.getBoundingClientRect().width;
            var widthModal = _this._elements.figure.getBoundingClientRect().width;
            var percentWidth =  Math.round(widthModal / widthDivBg * 100);
            var heightDivBg = divBackground.getBoundingClientRect().height;
            var heightModal = heightDivBg * (percentWidth/100);
            var wrap = _this._elements.figure.querySelector('.wrap-hover');
            wrap.style.height = heightModal + 'px';
        });

        var patternImage = new RegExp(section.id + '\\s*\\.' + className + '\\s*{[ \\n\\t\\ra-z0-9:()\'\\/.;_-]*background-image:\\s*url\\(\'?/?([^\']*)\'?\\);', 'im');

        var src = (style.match(patternImage) && style.match(patternImage)[1] !== '') ? style.match(patternImage)[1] : '';

        _this._elements.inputImage.querySelector('input').value = src;

        if (src !== '') {
            img.src = src;
            divImg.style.backgroundImage = 'url(\'' + src + '\')';
        }

        if (repeat && repeat[1] === 'repeat') {
            divImg.style.backgroundRepeat = 'repeat';
            divImg.style.webkitBackgroundRepeat = 'repeat';
        } else {
            divImg.style.backgroundRepeat = 'no-repeat';
            divImg.style.webkitBackgroundRepeat = 'no-repeat';
        }
        if (bgOptions && bgOptions[1] !== 'auto') {
            divImg.style.backgroundSize = bgOptions[1];
            divImg.style.webkitBackgroundSize = bgOptions[1];
        }

        var bgStyle = _this._elements.BgStyle;
        bgStyle.addEventListener('supra.check.select', function (e) {
            if (e.detail.toLowerCase() === 'repeat') {
                divImg.style.backgroundRepeat = 'repeat';
                divImg.style.webkitBackgroundRepeat = 'repeat';
            } else {
                divImg.style.backgroundRepeat = 'no-repeat';
                divImg.style.webkitBackgroundRepeat = 'no-repeat';
            }
            if (e.detail.toLowerCase() === 'auto' || e.detail.toLowerCase() === 'repeat') {
                divImg.style.backgroundSize = divImg.dataset.percent + '% auto';
                divImg.style.webkitBackgroundSize = divImg.dataset.percent + '% auto';
            } else {
                divImg.style.backgroundSize = e.detail.toLowerCase();
                divImg.style.webkitBackgroundSize = e.detail.toLowerCase();
            }
        });

        divImg.style.opacity = opacity ? opacity[1] : 1;
        divImg.style.backgroundPosition = window.getComputedStyle(divBackground, null).getPropertyValue("background-position");

        var range = _this._elements.inputRange.querySelector('input');
        range.addEventListener('input', function () {
            divImg.style.opacity = this.value / 100;
        });

        var background = this._elements.figure.querySelector('.bg-test');
        var bgClassName = section.className.match(/bg-.-color-(light|dark)/i);
        if (bgClassName) background.classList.add(bgClassName[0]);

        var argsSave = {
            image: _this._elements.inputImage ? _this._elements.inputImage.querySelector('input').value : ''
            , bgStyle: _this._elements.BgStyle ? _this._elements.BgStyle.querySelector('.dropdown button').dataset.value.toLowerCase() : ''
            , range: _this._elements.inputRange.querySelector('input').value / 100
        };

        //this is need to create new button because modal-footer will be overloaded
        this._footer.innerHTML = '<button type="button" class="supra-btn btn-default" data-dismiss="modal">Cancel</button>';

        var apply = this._getButton('supra-btn btn-primary', 'Apply', function() {
            var args = {
                image: _this._elements.inputImage ? _this._elements.inputImage.querySelector('input').value : ''
                , bgStyle: _this._elements.BgStyle ? _this._elements.BgStyle.querySelector('.dropdown button').dataset.value.toLowerCase() : ''
                , range: _this._elements.inputRange.querySelector('input').value / 100
            };

            _this._applyDivBg(_this, divBackground, args, argsSave);

            $(_this._selfDOM).modal('hide');
        });
        this._footer.appendChild(apply);
    }
    /**
     * 
     * @private
     */ 
    , _applyDivBg: function(_this, divBackground, args, argsSave) {
        args.image = builder.replaceQuotes(args.image);
        var li = controls.findParent(divBackground, ['section-item']);
        var className = divBackground.className.match(/half-container-\w*/)[0];
        var section = li.children[0];
        var style = li.querySelector('style');

        var pattern = new RegExp('(' + '#' + section.id + '\\s*\\.' + className + '\\s*{)([^\}]*)(\})', 'im');
        var bgOptionSize = args.bgStyle;
        var bgOptionRepeat = 'no-repeat';
        if (args.bgStyle === 'repeat') {
            bgOptionSize = 'auto';
            bgOptionRepeat = args.bgStyle;
        }

        if (style.innerHTML.search(pattern) !== -1) {
            style.innerHTML = style.innerHTML.replace(pattern, '$1'
                + _this._getBgStyle(args.image, bgOptionSize, bgOptionRepeat, args.range)
                + '$3');
        } else {
            style.innerHTML = '\n#' + section.id + ' .' + className + ' {'
                + _this._getBgStyle(args.image, bgOptionSize, bgOptionRepeat, args.range)
                + '}\n'
                + li.children[1].innerHTML;
        }

        builder.setStep(function () {
            _this._applyDivBg(_this, divBackground, argsSave, args);
        });
    }
    /**
     *
     * @private
     */
    , _getModalDemo: function (_this) {
        this._title.innerHTML = "<h5 class=\"text-center\">You're using demo version, download feature is only available in the full version of the builder.</h5>";

        this._elements = null;

        //this is need to create new button because modal-footer will be overloaded
        this._footer.innerHTML = '<button type="button" class="supra-btn btn-default" data-dismiss="modal">Cancel</button>';

        var apply = this._getButton('supra-btn btn-success', 'Buy full version', function() {

            window.location = _this._targetObject.dataset.href || window.location.href;

            $(_this._selfDOM).modal('hide');
        });
        this._footer.appendChild(apply);
    }
    /**
     * --------------------------------------- Some helpful functions --------------------------------------------
     */
    , _getBgStyle: function(image, bgOptionSize, bgOptionRepeat, opacityVal) {
        return '\n\tbackground-image: url(\''
            + image
            + '\');\n'
            + '\tbackground-size: ' + bgOptionSize + ';\n'
            + '\t-webkit-background-size: ' + bgOptionSize + ';\n'
            + '\tbackground-repeat: ' + bgOptionRepeat + ';\n'
            + '\t-webkit-background-repeat: ' + bgOptionRepeat + ';\n'
            + '\topacity: ' + opacityVal + ';\n';
    }
};