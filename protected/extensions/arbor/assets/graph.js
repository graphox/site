(function($){

	Renderer = function(canvas, sys){
		var canvas;
		var ctx;
		var particleSystem
		
		function onReseize()
		{

			canvas = $(canvas).get(0)
			ctx = canvas.getContext("2d");			
			canvas.width = $(canvas).parent().width();
			canvas.height = $(canvas).parent().height();
			sys.screenSize(canvas.width, canvas.height);
		}
		
		onReseize();
		$(window).resize(onReseize);
		

		var that = {
		  init:function(system){
			//
			// the particle system will call the init function once, right before the
			// first frame is to be drawn. it\'s a good place to set up the canvas and
			// to pass the canvas size to the particle system
			//
			// save a reference to the particle system for use in the .redraw() loop
			particleSystem = system

			// inform the system of the screen dimensions so it can map coords for us.
			// if the canvas is ever resized, screenSize should be called again with
			// the new dimensions
			particleSystem.screenSize(canvas.width, canvas.height) 
			particleSystem.screenPadding(80) // leave an extra 80px of whitespace per side

			// set up some event handlers to allow for node-dragging
			that.initMouseHandling()
		  },

		  redraw:function(){
			// 
			// redraw will be called repeatedly during the run whenever the node positions
			// change. the new positions for the nodes can be accessed by looking at the
			// .p attribute of a given node. however the p.x & p.y values are in the coordinates
			// of the particle system rather than the screen. you can either map them to
			// the screen yourself, or use the convenience iterators .eachNode (and .eachEdge)
			// which allow you to step through the actual node objects but also pass an
			// x,y point in the screen\'s coordinate system
			// 
			ctx.fillStyle = "white"
			ctx.fillRect(0,0, canvas.width, canvas.height)

			particleSystem.eachEdge(function(edge, pt1, pt2){
			  // edge: {source:Node, target:Node, length:#, data:{}}
			  // pt1:  {x:#, y:#}  source position in screen coords
			  // pt2:  {x:#, y:#}  target position in screen coords

			  // draw a line from pt1 to pt2
			 /* ctx.strokeStyle = "rgba(0,0,0, .333)"
			  ctx.lineWidth = 1
			  ctx.beginPath()
			  ctx.moveTo(pt1.x, pt1.y)
			  ctx.lineTo(pt2.x, pt2.y)
			  ctx.stroke()*/
			  
			 ctx.strokeStyle = "rgba(0,0,0, .333)"
			 ctx.fillStyle	 = "rgba(0,0,0, .333)"
			  
			  var arrow = function (ctx,p1,p2,size, offset, text){
					  ctx.save();
					  
					  offset = offset ? offset : 0

					  //var points = edges(ctx,p1,p2);
					  //if (points.length < 2) return 
					  //p1 = points[0], p2=points[points.length-1];

					  // Rotate the context to point along the path
					  var dx = p2.x-p1.x, dy=p2.y-p1.y, len=Math.sqrt(dx*dx+dy*dy);
					  ctx.translate(p2.x,p2.y);
					  ctx.rotate(Math.atan2(dy,dx));

					  // line
					  ctx.lineCap = 'round';
					  ctx.beginPath();
					  ctx.moveTo(-offset-size,0);
					  ctx.lineTo(-len+offset,0);
					  ctx.closePath();
					  ctx.stroke();

/*
						var b = ctx.fillStyle
						ctx.textAlign = 'center';
						ctx.font = '10pt Calibri';
						ctx.fillStyle = 'black';
						ctx.fillText(edge.data.type,
							-(len/2),
							-2
						)
						ctx.fillStyle = b

*/
					  // arrowhead
					  ctx.beginPath();
					  ctx.moveTo(0-offset, 0);
					  ctx.lineTo(-size-offset, (-size-offset)/2);
					  ctx.lineTo(-size-offset,	(size+offset)/2);
					  ctx.closePath();
					  ctx.fill();

					  ctx.restore();
		    }
		    
		    arrow(ctx, pt1, pt2, 10, 5)
  			
			})

			particleSystem.eachNode(function(node, pt){
			  // node: {mass:#, p:{x,y}, name:"", data:{}}
			  // pt:   {x:#, y:#}  node position in screen coords

			  // draw a rectangle centered at pt
			  var w = 10
			  ctx.fillStyle = (node.data.alone) ? "orange" : "black"
			  ctx.fillRect(pt.x-w/2, pt.y-w/2, w,w)
			  
			  	ctx.textAlign = 'center';
				ctx.font = '10pt Calibri';
				ctx.fillStyle = 'black';
				ctx.fillText(node.data.modelclass,
					pt.x,
					pt.y-8
				)
			})    			
		  },

		  initMouseHandling:function(){
			// no-nonsense drag and drop (thanks springy.js)
			var dragged = null;

			// set up a handler object that will initially listen for mousedowns then
			// for moves and mouseups while dragging
			var handler = {
			  clicked:function(e){
				var pos = $(canvas).offset();
				_mouseP = arbor.Point(e.pageX-pos.left, e.pageY-pos.top)
				dragged = particleSystem.nearest(_mouseP);

				if (dragged && dragged.node !== null){
				  // while we\'re dragging, don\'t let physics move the node
				  dragged.node.fixed = true
				}

				$(canvas).bind("mousemove", handler.dragged)
				$(window).bind("mouseup", handler.dropped)

				return false
			  },
			  dragged:function(e){
				var pos = $(canvas).offset();
				var s = arbor.Point(e.pageX-pos.left, e.pageY-pos.top)

				if (dragged && dragged.node !== null){
				  var p = particleSystem.fromScreen(s)
				  dragged.node.p = p
				}

				return false
			  },

			  dropped:function(e){
				if (dragged===null || dragged.node===undefined) return
				if (dragged.node !== null) dragged.node.fixed = false
				dragged.node.tempMass = 1000
				dragged = null
				$(canvas).unbind("ousemove", handler.dragged)
				$(window).unbind("mouseup", handler.dropped)
				_mouseP = null
				return false
			  }
			}

			// start listening
			$(canvas).mousedown(handler.clicked);

		  },

		}
		return that
	  }
})(jQuery);   
