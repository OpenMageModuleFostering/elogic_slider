;(function($, win) {
    var adjustment;
    var sliderForm = function() {
        this.init();
    };

    sliderForm.prototype = {
        constructor: sliderForm,
        init: function(){
            this.urls = win.urls || {};
            this.maxSlides = 5;
            this.slidesListContainer = $("ul#slides-list");
            this.slidesContainer = $('ul#slides');
            this.activeSlideId = null;
            this.selection = null;

            this.initEvents();
        },
        initEvents: function(){
            var me = this;
            $('body')
                .on('click','.slider-container .add-new-slide',function () {
                    var items = $('ul.slides-list li');

                    if(typeof items == 'undefined' || (items.length < me.maxSlides)){
                        Element.show('loading-mask');
                        me.sendAjax(me.urls.addSlide,{},me.addForm);
                    }else{
                        alert('Maximum ' + me.maxSlides + ' slides');
                    }
                })
                .on('click','.slider-container .slides-list li',function () {
                    var id = $(this).attr('id');

                    if(me.selection) {
                        me.selection.cancelSelection();
                    }

                    me.activeSlideId = id;
                    $(this).addClass('active').siblings().removeClass('active');
                    $('.slider-container .slides li#slide-' + id).show().siblings().hide();
                })
                .on('click','.slider-container .slides-list li i.icon-delete',function () {
                    var parent = $(this).closest('li'),id = parent.attr('id');

                    parent.remove();
                    $('.slider-container .slides li#slide-' + id).remove();
                    delete sliderConfig.slides[id];
                    sliderConfig.slides.length = --sliderConfig.slides.length;

                    if(parent.hasClass('active')){
                        me.initFirst();
                    }
                })
                .on('change','input[name=image]',function(){
                    var form = $(this).closest('form');

                    form.ajaxSubmit({
                        url: me.urls.thumbnailImage,
                        data: {format: 'json'},
                        type: 'post',
                        dataType: 'json',
                        mask: true,
                        beforeSubmit: function () {
                            Element.show('loading-mask');
                            //var validator = form.validate();
                            //if (!validator.element("input[type=file]")) {
                            //    alert('Allowed formats: jpeg, jpg, png');
                            //    return false;
                            //}
                        },
                        success: function (response) {
                            Element.hide('loading-mask');

                            if (response && response.success && response.path) {
                                var blockImg = form.find('.slide-image'),img = blockImg.find('img');
                                img.attr('src', response.path);
                                sliderConfig.slides[me.activeSlideId]['image'] = response.path;
                            }
                        }
                    });

                    return false;
                })
                .on('change','input[name=title]',function(){
                    sliderConfig.slides[me.activeSlideId]['title'] = $(this).val();
                })
                .on('submit','form.form-blocks',function(){
                    var form = $(this),parent = form.closest('li'),id = me.activeSlideId;

                    if(me.selection) {
                        me.selection.cancelSelection();
                    }
                    var imgWidth  = $('.slide-image',parent).width();
                    var imgHeight = $('.slide-image',parent).height();

                    var selectX1     = parseInt($('input[name="x1"]',parent).val());
                    var selectY1     = parseInt($('input[name="y1"]',parent).val());
                    var selectWidth  = parseInt($('input[name="width"]',parent).val());
                    var selectHeight = parseInt($('input[name="height"]',parent).val());
                    var productLink  = $('input[name="product-link"]',form).val();
                    var productName  = $('input[name="product-name"]',form).val();

                    if (!selectX1 || !selectY1 || !selectWidth || !selectHeight || !productLink || !productName) {
                        console.log('You\'ve missed some data');
                        return false;
                    }

                    $('input',form).val('');

                    var X1     = (selectX1 / imgWidth);
                    var Y1     = (selectY1 / imgHeight);
                    var width  = (selectWidth / imgWidth);
                    var height = (selectHeight / imgHeight);

                    function Element(x1, y1, width, height, url, name) {
                        this.x1 = x1,
                            this.y1 = y1,
                            this.width = width,
                            this.height = height,
                            this.url = url,
                            this.name = name
                    }

                    var item = new Element(X1, Y1, width, height, productLink, productName);

                    if(typeof sliderConfig.slides[id]['blocks'] === 'undefined' ){
                        sliderConfig.slides[id]['blocks'] = [];
                    }
                    sliderConfig.slides[id]['blocks'].push(item);
                    var blocks = sliderConfig.slides[id]['blocks'],
                        index = $('.el_beatles',parent).size();

                    $('.wrapper .blocks',parent).append('<a class="el_beatles el_'+ index +'"><span>');
                    $('.wrapper .blocks',parent).append('<div class="el el_' + index + '_info"><p class="el_titles el_title_' + index +'">');
                    $('.wrapper .el_' + index + '_info',parent).append('<button class="delete_item delete_el_' + index + '">Delete</button>');

                    blocks.forEach(function(item, i) {
                        $('.el_' + i,parent).css({
                            'left' : parseInt(imgWidth * item.x1),
                            'top' : parseInt(imgHeight * item.y1),
                            'width' : parseInt(imgWidth * item.width),
                            'height' : parseInt(imgHeight * item.height)
                        }).attr('href', item.url);
                        $('.el_' + i + ' span',parent).text(item.name);
                        $('.el_title_' + i,parent).text(item.name + ' [' + item.url + ']');
                    });
                    return false;
                })
                .on('click', '.delete_item' ,function() {
                    var item = $(this).parent().prev()[0].innerText,id = me.activeSlideId,
                        blocks = sliderConfig.slides[id]['blocks'];
                    $(this).parent().prev().remove();
                    $(this).parent().remove();

                    blocks.forEach(function(element, i) {
                        if ( element.name == item) {
                            blocks.splice(i, 1);
                        }
                    });
                    return false;
                })
                .on('click','.save-slider',function() {
                    var validator = new Validation(editForm.formId),
                        form = $('#' + editForm.formId);

                    if(validator && validator.validate()){
                        var data = {};
                        form.serializeArray().map(function(x){
                            data[x.name] = x.value;
                        });

                        data['slides'] = [];

                        for(var key in sliderConfig.slides){
                            if(typeof sliderConfig.slides[key] == 'object'){
                                var slide = [];
                                if(typeof sliderConfig.slides[key]['id'] != 'undefined'){
                                    slide.push({'id':sliderConfig.slides[key]['id']})
                                }

                                if(typeof sliderConfig.slides[key]['title'] != 'undefined'){
                                    slide.push({'title':sliderConfig.slides[key]['title']})
                                }

                                if(typeof sliderConfig.slides[key]['image'] != 'undefined'){
                                    slide.push({'image':sliderConfig.slides[key]['image']})
                                }

                                if(typeof sliderConfig.slides[key]['position'] != 'undefined'){
                                    slide.push({'position':sliderConfig.slides[key]['position']})
                                }

                                if(typeof sliderConfig.slides[key]['blocks'] != 'undefined'){
                                    slide.push({'blocks':sliderConfig.slides[key]['blocks']})
                                }

                                data['slides'].push(slide);
                            }
                        }

                        Element.show('loading-mask');
                        me.sendAjax(me.urls.saveSlider,data);
                    }
                    return false;
                })
                .on('click','.slide-image a',function(){
                    return false;
                })
             ;
            me.slidesListContainer.on('updateOrder',function(){
                $('li',me.slidesListContainer).each(function(){
                    var id = $(this).attr('id'),pos = $(this).index();
                    $('.slider-container .slides li#slide-' + id).find('input[name=position]').val(pos);
                    sliderConfig.slides[id]['position'] = pos;
                });
            });

            $.when(me.initParams()).done(function(){
                me.initFirst();
            });
        },
        sendAjax: function(url,data,callback,parent){
            var me = this;
            $.ajax({
                url: url,
                type:"POST",
                data: data,
                dataType: 'json'
            }).done(function(resp) {
                Element.hide('loading-mask');

                if(resp.message){
                    alert(resp.message);
                }

                if(resp && resp.success){
                    if(typeof callback == 'function'){
                        callback.call(me,resp,parent);
                    }

                    if(resp.redirect){
                        setTimeout(function(){window.location.href = resp.redirect},500);
                    }
                }
            }) .fail(function( jqXHR, textStatus, errorThrown) {
                Element.hide('loading-mask');
            }).always(function() {
                Element.hide('loading-mask');
            });
        },
        initFirst: function()
        {
            var first = $('li:first-child',this.slidesListContainer);
            if(first){
                first.trigger('click');
            }
        },
        addForm: function(resp){
            this.slidesListContainer.append(resp.row);
            this.slidesContainer.append(resp.html);

            var newSlide = $('li:last-child',this.slidesContainer),idVal = newSlide.find('[name=uniqid]').val();

            sliderConfig.slides[idVal] = [];
            sliderConfig.slides.length = ++sliderConfig.slides.length;
            $('li:last-child',this.slidesListContainer).trigger('click');

            var slideImage = $('img.slide-image',newSlide);

            this.selection = slideImage.imgAreaSelect({instance: true});

            slideImage.imgAreaSelect({
                handles: true,
                movable: true,
                onSelectEnd: function (img, selection) {
                    $('input[name="x1"]',newSlide).val(selection.x1);
                    $('input[name="y1"]',newSlide).val(selection.y1);
                    $('input[name="width"]',newSlide).val(selection.width);
                    $('input[name="height"]',newSlide).val(selection.height);
                }
            });
            this.slidesListContainer.trigger("updateOrder");
        },
        initParams: function(){
            var me = this;

            $('.sortable').sortable({
                handle: 'i.icon-move',
                onDrop: function ($item, container, _super) {
                    var $clonedItem = $('<li/>').css({height: 10});
                    $item.before($clonedItem);
                    $clonedItem.animate({'height': $item.height()});

                    $item.animate($clonedItem.position(), function () {
                        $clonedItem.detach();
                        _super($item, container);
                    });
                    setTimeout(function(){$("ul#slides-list").trigger("updateOrder")},1000);
                },
                onDragStart: function ($item, container, _super) {
                    var offset = $item.offset(),
                        pointer = container.rootGroup.pointer;
                    adjustment = {
                        left: pointer.left - offset.left,
                        top: pointer.top - offset.top
                    };
                    _super($item, container);
                },
                onDrag: function ($item, position) {
                    $item.css({
                        left: position.left - adjustment.left,
                        top: position.top - adjustment.top
                    });
                }
            });

            if(sliderConfig['slides']){
                $.each(sliderConfig['slides'],function(index, value){
                    var container = me.slidesContainer.find('li#slide-' + value['id']);

                    container.show();

                    var img = container.find('.slide-image img'),
                    imgWidth = img.width(),imgHeight = img.height();

                    img.imgAreaSelect({
                        handles: true,
                        movable: true,
                        onSelectEnd: function (img, selection) {
                            $('input[name="x1"]',container).val(selection.x1);
                            $('input[name="y1"]',container).val(selection.y1);
                            $('input[name="width"]',container).val(selection.width);
                            $('input[name="height"]',container).val(selection.height);
                        }
                    });
                    container.hide();

                    if(value['blocks']){
                        $('.wrapper .blocks',container).html('');
                        value['blocks'].forEach(function(item, i) {
                            $('.wrapper .blocks',container).append('<a class="el_beatles el_'+ i +'"><span>');
                            $('.wrapper .blocks',container).append('<div class="el el_' + i + '_info"><p class="el_titles el_title_' + i +'">');
                            $('.wrapper .blocks .el_' + i + '_info',container).append('<button class="delete_item delete_el_' + i + '">Delete</button>');
                            $('.el_' + i,container).css({
                                'left': parseInt(imgWidth * item.x1),
                                'top': parseInt(imgHeight * item.y1),
                                'width': parseInt(imgWidth * item.width),
                                'height': parseInt(imgHeight * item.height)
                            }).attr('href', item.url);
                            $('.el_' + i + ' span',container).text(item.name);
                            $('.el_title_' + i,container).text(item.name + ' [' + item.url + ']');
                        });
                    }
                });
            }
        }
    };
    $(function() {
        window.sliderForm = new sliderForm();
    })
})(jQuery, window);
