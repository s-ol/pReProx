#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>

int main( int argc, char *argv[] ) {
 char call[200];
 pid_t pid = fork();

 if ( argc != 5 ) {
                puts( "wrong number of parameters" );
                return 1;
        }

 if ( !pid ) {
  sprintf( call, "./redirect.sh %s %s %s", argv[1], argv[2], argv[3] );
  system( call );
  return 0;
 }

 int time = atoi( argv[4] );
 sleep( time );
 sprintf( call, "kill %d", pid );
 kill( pid );
 system( call );
 printf( "killed %d\n", pid );
}
