MAX = 10000
N = 3

for a in range(3, MAX):
	for b in range(a, MAX):
		c = b + N
		if a*a + b*b == c*c and a % N != 0:
			print(a,b,c)


# for(var a=3;a<max;a++){
# 	for(var b=a;b<max;b++){
# 		if(a*a + b*b === c*c && a % n != 0){
# 			document.write(a + " " + b + " " + c + "<br>");
# 		}
# 	}
# }