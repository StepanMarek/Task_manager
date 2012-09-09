function GOLBackground(dom, width, height, scale, size){
	this.dom = dom;
	this.width = width;
	this.height = height;
	this.scale = scale;
	this.size = size;

	this.board = [];
	for(var x=0;x<width/scale;x++){
		this.board[x] = [];
		for(var y=0;y<height/scale;y++){
			this.board[x][y] = Math.round(Math.random());
		}
	}

	this.canvas = document.createElement("canvas");
	this.canvas.width = width;
	this.canvas.height =  height;
	this.ctx = this.canvas.getContext("2d");

	this.count_neighbours = function(board, x,y){
		var pocet = 0;
		var width = board.length,
			height = board[0].length;
		var left = (x-1 + width) % width,
			right = (x+1) % width,
			top = (y-1 + height) % height,
			bottom = (y+1) % height;

		pocet += board[left][top];
		pocet += board[left][y];
		pocet += board[left][bottom];
		pocet += board[x][top];
		pocet += board[x][bottom];
		pocet += board[right][top];
		pocet += board[right][y];
		pocet += board[right][bottom];

		return pocet
	}

	this.update = function(generations){
		for(var i=0;i<generations;i++){
			var newBoard = [];
			for(var x=0;x<width/scale;x++){
				newBoard[x] = [];
				for(var y=0;y<height/scale;y++){
					neighbours = this.count_neighbours(this.board, x,y);
					if(neighbours == 2){
						newBoard[x][y] = this.board[x][y];
					}
					else if(neighbours == 3){
						newBoard[x][y] = 1;
					}
					else {
						newBoard[x][y] = 0;
					}
				}
			}
			this.board = newBoard;
		}
	}

	this.mutate = function(ratio){
		for(var x=0;x<width/scale;x++){
			for(var y=0;y<height/scale;y++){
				if( Math.random() < ratio ){
					this.board[x][y] =  1;
				}
			}
		}
	}

	this.render = function(){

		var grey = 200;
		var bg = 250;

		this.ctx.fillStyle = "rgb("+bg+","+bg+","+bg+")";
		this.ctx.fillRect(0,0,this.width, this.height)

		var image = this.ctx.getImageData(0,0,this.width,this.height);

		for(var x=0;x<this.width;x+=this.scale){
			for(var y=0;y<this.height;y+=this.scale){
				var p = (y*this.width + x)*4;
				if(this.board[x/this.scale][y/this.scale] === 1){

					for(var xs=0;xs<this.size;xs++){
						for(var ys=0;ys<this.size;ys++){
							var s = p + (ys*this.width + xs)*4;
								
							image.data[s] = grey;
							image.data[s+1] = grey;
							image.data[s+2] = grey;
							image.data[s+3] = 255;
						}
					}
				}
			}
		}

		this.ctx.putImageData(image, 0, 0);

		var imgData = this.canvas.toDataURL();

		this.dom.style.backgroundImage = "url(" +imgData+ ")";
	}
}
