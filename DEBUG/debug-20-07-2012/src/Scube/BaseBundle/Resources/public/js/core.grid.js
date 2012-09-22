
/* function to fix the -10000 pixel limit of jquery.animate */
$.fx.prototype.cur = function(){
    if ( this.elem[this.prop] != null && (!this.elem.style || this.elem.style[this.prop] == null) ) {
      return this.elem[ this.prop ];
    }
    var r = parseFloat( jQuery.css( this.elem, this.prop ) );
    return typeof r == 'undefined' ? 0 : r;
}

var WidgetsOnGrid = new Array();

var page_width;
var page_height;
var cells_per_line;
var nb_line;
var	cell_width;
var	cell_height;
var	border;
var back_shadow = 0;
var fullscreen = false;
var edit_mode = false;
var admin_mode = false;
var widgets_opacity = 0.7;
var grid_scroller = false;

function switch_edit_mode(value)
{
	if (admin_mode)
		clean_grid();
	if (value)
		edit_mode = value;
	else if (edit_mode)
		edit_mode = false;
	else
		edit_mode = true;
	
	load_Grid();
}

function switch_admin_mode(value)
{
	if (edit_mode || value == false)
		clean_grid();
	if (value)
		admin_mode = value;
	else if (admin_mode)
		admin_mode = false;
	else
		admin_mode = true;
	
	if (admin_mode == true)
		load_AdminGrid();
}

function init_Widgets_Array()
{
	for (var i=0; i!=nb_line; ++i) {
		WidgetsOnGrid[i] = new Array();
		for (var j=0; j!=cells_per_line; ++j) {
			WidgetsOnGrid[i][j] = 0;
		}
	}
}

function clean_grid()
{
	$("#Grid").html("");
}

function add_WidgetOnGrid(id, pos_x, pos_y, width, height)
{
	if (Widgets[id]['type'] == "button")
		$("#Grid").append("<div class='plain_cell widget AppBox' id='" + id + "' href='" + Widgets[id]['button_link'] + "'></div>");
	else
		$("#Grid").append("<div class='plain_cell widget' id='" + id + "'></div>");
	if (edit_mode)
		$("#"+id).append("<div class='cell_anchor'></div><div class='cell_remove'></div>");
	else
	{
		if (Widgets[id]['fullscreen'])
			$("#"+id).append("<div class='cell_fullscreen'>");
	}
	
	$("#"+id+" .cell_fullscreen").click(function() {
		
		if (fullscreen)
		{
			fullscreen = false;
			move_Widget(id, Widgets[id]['pos_x'], Widgets[id]['pos_y']);
			//$("#"+id).draggable({ disabled: false });
		}
		else
		{
		fullscreen = true;
		$("#"+id).draggable({ disabled: true });
		
	  	$("#"+id).animate({'width':page_width - cell_border*2,
					'height':page_height - $("#header").height() - 50,
					'left':0,
					'top':0,
					'z-index':111,
					opacity:1
					});
		}
	});
	
	$("#"+id+" .cell_remove").click(function(event) {
		delete_widget_from_grid(id);
		event.preventDefault();
		event.stopPropagation();
	});
	
	$("#"+id).css({'width':cell_width*width + (cell_border*(width-1)),
					'height':cell_height*height + (cell_border*(height-1)),
					'left':cell_width*pos_x + cell_border*pos_x,
					'top':cell_height*pos_y + cell_border*pos_y,
					'z-index':110,
					'opacity':widgets_opacity
					});
	
	load_widget(id);
	
	if (edit_mode)
	$("#"+id).draggable({revert:'invalid', handle:".cell_anchor",
							start:function(event, ui) {
											$("#"+id).animate({'width':((cell_width*width + (cell_border*(width-1)))*3)/4 ,
											'height':((cell_height*height + (cell_border*(height-1)))*3)/4
											}, 	0);
									},
							stop: function(event, ui) {	
											$("#"+id).animate({'width':cell_width*width + (cell_border*(width-1)),
											'height':cell_height*height + (cell_border*(height-1))
											}, 	0);
							}
							});
	if (edit_mode)
		$("#"+id).resizable({grid:cells_per_line}); 

	for (var i=pos_y; i!=(pos_y+height); ++i) {
		for (var j=pos_x; j!=(pos_x+width); ++j) {
			WidgetsOnGrid[i][j] = id;
		}
	}
}

function add_AdminWidgetOnGrid(id, pos_x, pos_y, width, height)
{
	if ($("#"+id).length)
		$("#"+id).remove();
	$("#Grid").append("<div class='plain_cell widget' id='" + id + "'></div>");
	
	$("#"+id).css({'width':cell_width*width + (cell_border*(width-1)),
					'height':cell_height*height + (cell_border*(height-1)),
					'left':cell_width*pos_x + cell_border*pos_x,
					'top':cell_height*pos_y + cell_border*pos_y,
					'z-index':110,
					'opacity':widgets_opacity
					});
	
	load_adminWidget(id);
}

function load_Grid()
{
	page_width = $(window).width();
	page_height = $(window).height();
	header_height = parseInt($("#header").height()) + parseInt($("#header").css('margin-top').replace("px", "")) + parseInt($("#header").css('margin-bottom').replace("px", ""));
	cell_border = 10;
	cell_width = Math.round((page_width - cell_border) / cells_per_line) - cell_border;
	cell_height = cell_width;
	
	$("#GridContainer").css({"height": page_height - header_height})
	$("#Grid").css({"margin":"0 "+cell_border+"px", "height":cell_width*nb_line + cell_border*nb_line})
	
	init_Widgets_Array();
	
	if ($('#Grid').length)
		$('#Grid').html("");
	for (var i=0; i!=nb_line; ++i) {
		for (var j=0; j!=cells_per_line; ++j) {
			var cell_id = "empty_cell_"+i+"_"+j;
			$("#Grid").append("<div class='empty_cell' id='" + cell_id + "'></div>");
			$("#"+cell_id).css({'width':cell_width,
							    'height':cell_height,
								'left':cell_width*j + cell_border*j,
								'top':cell_height*i + cell_border*i,
								'z-index':100
								});
			if (!edit_mode)
				$("#"+cell_id).css({
							'background-color':"transparent"
							});
			else
			{
				$("#"+cell_id).hover(
				  function () {
					$(this).addClass("empty_cell_hover");
					/* Get x and y from cell id */
					id_splitted = $(this).attr('id').split("_");
					load_empty_cells(id_splitted[3], id_splitted[2]);
				  },
				  function () {
					unload_empty_cells();
					$(this).removeClass("empty_cell_hover");
				  }
				);
			}
			if (edit_mode)
			$("#"+cell_id).droppable( {
      								accept: '.widget',
      								hoverClass: 'cell_hover',
									tolerance:'pointer',
      								drop: handleCellDrop,
									over:
									function ( event, ui ) {
										var drag_id = ui.draggable.attr( 'id' );
											var drag_id = ui.draggable.attr( 'id' );
											$(this).css({
															'width': (cell_width*Widgets[drag_id]['width'] + (cell_border*(Widgets[drag_id]['width']-1))),
															'height':(cell_height*Widgets[drag_id]['height'] + (cell_border*(Widgets[drag_id]['height']-1))),
															'z-index':101
															});
									},
									out:
									function ( event, ui ) {
									back_shadow = 0;
									var drag_id = ui.draggable.attr( 'id' );
									$(this).css({
													'width':cell_width,
													'height':cell_height,
													'z-index':100
													});
								}
    		} );
		}
	}
	/*Load les exemples*/
	for (id in Widgets) {
		add_WidgetOnGrid(id, Widgets[id]['pos_x'], Widgets[id]['pos_y'], Widgets[id]['width'], Widgets[id]['height']);
	}
	
	if (grid_scroller == false)
		grid_scroller = $('#GridContainer').jScrollPane({hideFocus:true});
	else
		grid_scroller.data('jsp').reinitialise();
}

function load_AdminGrid()
{
	page_width = $(window).width();
	page_height = $(window).height();
	header_height = parseInt($("#header").height()) + parseInt($("#header").css('margin-top').replace("px", "")) + parseInt($("#header").css('margin-bottom').replace("px", ""));
	cell_border = 10;
	cell_width = Math.round((page_width - cell_border) / cells_per_line) - cell_border;
	cell_height = cell_width;
	
	init_Widgets_Array();
	
	x=0;
	y=0;
	/*Load les exemples*/
	for (id in AdminWidgets) {
		add_AdminWidgetOnGrid(id, x++, y, 1, 1);
		if (x == cells_per_line) {
			y++;
			x = 0;
		}
	}
	
	if (grid_scroller == false)
		grid_scroller = $('#GridContainer').jScrollPane({hideFocus:true});
	else
		grid_scroller.data('jsp').reinitialise();
}

function move_Widget(id, pos_x, pos_y)
{
	Widgets[id].pos_x = pos_x;
	Widgets[id].pos_y = pos_y;
	save_moving_position(id, pos_x, pos_y);
	
	/* Delete the old widget position on grid */
	for (var i=0; i!=nb_line; ++i) {
		for (var j=0; j!=cells_per_line; ++j) {
			if (WidgetsOnGrid[i][j] == id)
				WidgetsOnGrid[i][j] = 0;
		}
	}

  limit_y = parseInt(pos_y) + parseInt(Widgets[id]['height']);
  limit_x = parseInt(pos_x) + parseInt(Widgets[id]['width']);
	/* Set the new positions on grid */
	for (var i=pos_y; i!=limit_y; ++i) {
		for (var j=pos_x; j!=limit_x; ++j) {
			WidgetsOnGrid[i][j] = id;
		}
	}
	$('#'+id).animate( {
      left: cell_width*pos_x + cell_border*pos_x+'px',
      top: cell_height*pos_y + cell_border*pos_y+'px',
	  width:cell_width*parseInt(Widgets[id]['width']) + (cell_border*(parseInt(Widgets[id]['width'])-1)),
	  height:cell_height*parseInt(Widgets[id]['height']) + (cell_border*(parseInt(Widgets[id]['height'])-1)),
	  'z-index':110,
	  'opacity':widgets_opacity
    } );
}



function handleCellDrop( event, ui ) {
  var drag_id = ui.draggable.attr( 'id' );
  var drop_id = $(this).attr( 'id' );
  
  /* Re-set the grid */
  for (var i=0; i!=nb_line; ++i) {
		for (var j=0; j!=cells_per_line; ++j) {
			var cell_id = "empty_cell_"+i+"_"+j;
			$("#"+cell_id).removeClass('cell_hover').css({'width':cell_width,
							    'height':cell_height,
								'z-index':100
								});
		}
	}
  
  
  drop_pos = drop_id.split("_");
  drop_pos = {"pos_y":drop_pos[2],"pos_x":drop_pos[3]};
  var authorization = true;
  
  limit_y = parseInt(drop_pos.pos_y) + parseInt(Widgets[drag_id]['height']);
  limit_x = parseInt(drop_pos.pos_x) + parseInt(Widgets[drag_id]['width']);
  
  if (limit_y > nb_line) limit_y = nb_line - parseInt(Widgets[drag_id]['height']);
  if (limit_x > cells_per_line) limit_x = cells_per_line - parseInt(Widgets[drag_id]['width']);
  
  if (drop_pos.pos_y >= limit_y) authorization = false;
  if (drop_pos.pos_x >= limit_x) authorization = false;

  for (var i=drop_pos.pos_y; i!=limit_y; i++) {

		for (var j=drop_pos.pos_x; j!=limit_x; j++) {
			if (WidgetsOnGrid[i][j] != 0 && WidgetsOnGrid[i][j] != drag_id) { /* The widget cant be move */
				authorization = false;
				break;
			}
		}
		if (authorization == false) {break;}
	}
	
	if (authorization == true) {
		move_Widget(drag_id, drop_pos.pos_x, drop_pos.pos_y);
	}
	else {
		move_Widget(drag_id, Widgets[drag_id]['pos_x'], Widgets[drag_id]['pos_y']);
	}
}