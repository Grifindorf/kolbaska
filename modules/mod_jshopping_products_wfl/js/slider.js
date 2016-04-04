/*
# $Id: slider.js
# package mod_jshopping_products_wfl
# file slider.js
# author Aleksey M. Abrosimov wflab
# url http://wflab.ru
# copyright (C) 2013 Web Face Laboratory All rights reserved
# license http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
*/
var wflSliders = ({});
var wflSlider = new Class({
    Implements:[Options,Events],
    initialize:function(options){
        this.setOptions(options);
        if(this.options.ribbon_behavior == 'scroll'){
            this.wrapper = $('rand_products_wfl_'+this.options.id);
            if(this.wrapper){
            this.ribbon = this.wrapper.getElement('.jspw_ribbon');
            this.scroll_r = this.wrapper.getElement('.jspw_scroll');
            this.lt = this.wrapper.getElement('.jspw_ribon_button_lt');
            this.rb = this.wrapper.getElement('.jspw_ribon_button_rb');
            this.blocks = this.wrapper.getElements('.jspw_prod');
            var blockSize = this.blocks[0].getComputedSize();
            var dim = (this.options.orientation == 'hor')?'x':'y';
            if(this.options.effect_block == 'single'){
                this.offset=(dim=='x')?blockSize.totalWidth:blockSize.totalHeight;
            }
            else{
                this.offset = (dim=='x')?this.ribbon.getComputedSize().totalWidth:this.ribbon.getComputedSize().totalHeight;
                this.offset+=0;
            }
            var caW = 0;
            this.blocks.each(function(el){caW+=el.getSize()[dim]});
            this.scroll_r.setStyle((dim=='x')?'width':'height',caW);
                if(this.lt){
                    this.lt.addClass('inactive');
                    if(this.ribbon.getSize()[dim] < this.scroll_r.getSize()[dim]){
                        this.rb.removeEvents('click');
                        this.rb.addEvent('click',function(ev){
                            if(this.rb.hasClass('inactive')) return false;
                            var scD = Math.min(this.scroll_r.getSize()[dim]-this.ribbon.getScroll()[dim], this.scroll_r.getSize()[dim]-this.offset,this.offset);
                            var nScroll1 = new Fx.Scroll(this.ribbon,{
                                duration:this.options.duration,
                                onComplete:function(){
                                    this.lt.removeEvents('click');
                                    this.lt.removeClass('inactive');
                                    if(this.ribbon.getScroll()[dim] + this.ribbon.getSize()[dim] >= this.scroll_r.getSize()[dim]) this.rb.addClass('inactive');
                                    this.lt.addEvent('click',function(ev){
                                    if(this.lt.hasClass('inactive')) return false;
                                    var scD = Math.min(this.scroll_r.getSize()[dim]-this.ribbon.getScroll()[dim], this.scroll_r.getSize()[dim]-this.offset, this.offset);
                                    var nScroll2 = new Fx.Scroll(this.ribbon,{
                                        duration:this.options.duration,
                                        onComplete:function(){
                                            if(this.ribbon.getScroll()[dim]==0) this.lt.addClass('inactive');
                                            this.rb.removeClass('inactive');
                                        }.bind(this)
                                    });
                                    if(dim == 'x'){
                                        nScroll2.start(this.ribbon.getScroll()[dim]-scD,0);
                                        }
                                    else{
                                        nScroll2.start(0,this.ribbon.getScroll()[dim]-scD);
                                    }
                                    }.bind(this));
                                }.bind(this)
                            });
                            if(dim == 'x'){
                                nScroll1.start(this.ribbon.getScroll()[dim]+scD,0);
                            }
                            else{
                                nScroll1.start(0,this.ribbon.getScroll()[dim]+scD);
                            }
                        }.bind(this));
                        //var aS = (function(){this.rb.fireEvent('click')}).periodical(3000,this);
                        //$$('.jspw_ribbon')[0].scrollTo(0);for(var i=0;i<3;i++){$$('.jspw_prod').getLast().inject($$('.jspw_prod')[0],'after')}  $$('.jspw_ribon_button_rb')[0].removeClass('inactive')
                    }
                }
            }
        }
    }
});

