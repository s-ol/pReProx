#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>

int main( int argc, char *argv[] ) {
  char call[200];
  pid_t pid = fork();

  if ( argc != 5 ) {
    puts( "wrong number of parameters\n" );
    return 1;
  }

  if ( !pid ) {
    execl( "./redirect.sh", "redirect.sh", argv[1], argv[2], argv[3], NULL );
    return 0;
  }

  int time = atoi( argv[4] );
  sleep( time );
  sprintf( call, "kill %d", pid );
  system( call );
  printf( "killed %d\n", pid );
}
