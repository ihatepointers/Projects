#include <stdio.h>
#include <stdlib.h>
#include <time.h>
#include <mpi.h>
#include <assert.h>
#include <string.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <sys/time.h>
#include <fcntl.h>
#include <unistd.h>
#include <time.h>

//Common.h start
#ifndef LITTLE_ENDIAN
# define LITTLE_ENDIAN
#endif

typedef unsigned char	byte;
typedef unsigned int	uint;
typedef unsigned int	uint32;
//Common.h end

//SHA1.hpp start
#ifndef _RAR_SHA1_
#define _RAR_SHA1_

#define HW 5

typedef struct {
    uint32 state[5];
    uint32 count[2];
    unsigned char buffer[64];
} hash_context;

void hash_initial( hash_context * c );
void hash_process( hash_context * c, unsigned char * data, unsigned len );
void hash_final( hash_context * c, uint32[HW] );

#endif
//SHA1.hpp end

//Rjindael.hpp start
#ifndef _RIJNDAEL_H_
#define _RIJNDAEL_H_

/**************************************************************************
 * This code is based on Szymon Stefanek AES implementation:              *
 * http://www.esat.kuleuven.ac.be/~rijmen/rijndael/rijndael-cpplib.tar.gz *
 *                                                                        *
 * Dynamic tables generation is based on the Brian Gladman's work:        *
 * http://fp.gladman.plus.com/cryptography_technology/rijndael            *
 **************************************************************************/

#define _MAX_KEY_COLUMNS (256/32)
#define _MAX_ROUNDS      14
#define MAX_IV_SIZE      16

class Rijndael
{
  public:
    enum Direction { Encrypt , Decrypt };
  private:
    void keySched(byte key[_MAX_KEY_COLUMNS][4]);
    void keyEncToDec();
    void encrypt(const byte a[16], byte b[16]);
    void decrypt(const byte a[16], byte b[16]);
    void GenerateTables();

    Direction m_direction;
    byte     m_initVector[MAX_IV_SIZE];
    byte     m_expandedKey[_MAX_ROUNDS+1][4][4];
  public:
    Rijndael();
    void init(Direction dir,const byte *key,byte *initVector);
    int blockEncrypt(const byte *input, int inputLen, byte *outBuffer);
    int blockDecrypt(const byte *input, int inputLen, byte *outBuffer);
};
#endif
//Rjindael.hpp end


//Rjindael.cpp start
const int uKeyLenInBytes=16, m_uRounds=10;

static byte S[256],S5[256],rcon[30];
static byte T1[256][4],T2[256][4],T3[256][4],T4[256][4];
static byte T5[256][4],T6[256][4],T7[256][4],T8[256][4];
static byte U1[256][4],U2[256][4],U3[256][4],U4[256][4];


inline void Xor128(byte *dest,const byte *arg1,const byte *arg2)
{
#if defined(PRESENT_INT32) && defined(ALLOW_NOT_ALIGNED_INT)
  ((uint32*)dest)[0]=((uint32*)arg1)[0]^((uint32*)arg2)[0];
  ((uint32*)dest)[1]=((uint32*)arg1)[1]^((uint32*)arg2)[1];
  ((uint32*)dest)[2]=((uint32*)arg1)[2]^((uint32*)arg2)[2];
  ((uint32*)dest)[3]=((uint32*)arg1)[3]^((uint32*)arg2)[3];
#else
  for (int I=0;I<16;I++)
    dest[I]=arg1[I]^arg2[I];
#endif
}


inline void Xor128(byte *dest,const byte *arg1,const byte *arg2,
                   const byte *arg3,const byte *arg4)
{
#if defined(PRESENT_INT32) && defined(ALLOW_NOT_ALIGNED_INT)
  (*(uint32*)dest)=(*(uint32*)arg1)^(*(uint32*)arg2)^(*(uint32*)arg3)^(*(uint32*)arg4);
#else
  for (int I=0;I<4;I++)
    dest[I]=arg1[I]^arg2[I]^arg3[I]^arg4[I];
#endif
}


inline void Copy128(byte *dest,const byte *src)
{
#if defined(PRESENT_INT32) && defined(ALLOW_NOT_ALIGNED_INT)
  ((uint32*)dest)[0]=((uint32*)src)[0];
  ((uint32*)dest)[1]=((uint32*)src)[1];
  ((uint32*)dest)[2]=((uint32*)src)[2];
  ((uint32*)dest)[3]=((uint32*)src)[3];
#else
  for (int I=0;I<16;I++)
    dest[I]=src[I];
#endif
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// API
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

Rijndael::Rijndael()
{
  if (S[0]==0)
    GenerateTables();
}


void Rijndael::init(Direction dir,const byte * key,byte * initVector)
{
  m_direction = dir;

  byte keyMatrix[_MAX_KEY_COLUMNS][4];

  for(uint i = 0;i < (uint)uKeyLenInBytes;i++)
    keyMatrix[i >> 2][i & 3] = key[i];

  for(int i = 0;i < MAX_IV_SIZE;i++)
    m_initVector[i] = initVector[i];

  keySched(keyMatrix);

  if(m_direction == Decrypt)
    keyEncToDec();
}



int Rijndael::blockDecrypt(const byte *input, int inputLen, byte *outBuffer)
{
  if (input == 0 || inputLen <= 0)
    return 0;

  byte block[16], iv[4][4];
  memcpy(iv,m_initVector,16);

  int numBlocks=inputLen/16;
  for (int i = numBlocks; i > 0; i--)
  {
    decrypt(input, block);
    Xor128(block,block,(byte*)iv);
#if STRICT_ALIGN
    memcpy(iv, input, 16);
    memcpy(outBuf, block, 16);
#else
    Copy128((byte*)iv,input);
    Copy128(outBuffer,block);
#endif
    input += 16;
    outBuffer += 16;
  }

  memcpy(m_initVector,iv,16);

  return 16*numBlocks;
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ALGORITHM
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


void Rijndael::keySched(byte key[_MAX_KEY_COLUMNS][4])
{
  int j,rconpointer = 0;

  // Calculate the necessary round keys
  // The number of calculations depends on keyBits and blockBits
  int uKeyColumns = m_uRounds - 6;

  byte tempKey[_MAX_KEY_COLUMNS][4];

  // Copy the input key to the temporary key matrix

  memcpy(tempKey,key,sizeof(tempKey));

  int r = 0;
  int t = 0;

  // copy values into round key array
  for(j = 0;(j < uKeyColumns) && (r <= m_uRounds); )
  {
    for(;(j < uKeyColumns) && (t < 4); j++, t++)
      for (int k=0;k<4;k++)
        m_expandedKey[r][t][k]=tempKey[j][k];

    if(t == 4)
    {
      r++;
      t = 0;
    }
  }

  while(r <= m_uRounds)
  {
    tempKey[0][0] ^= S[tempKey[uKeyColumns-1][1]];
    tempKey[0][1] ^= S[tempKey[uKeyColumns-1][2]];
    tempKey[0][2] ^= S[tempKey[uKeyColumns-1][3]];
    tempKey[0][3] ^= S[tempKey[uKeyColumns-1][0]];
    tempKey[0][0] ^= rcon[rconpointer++];

    if (uKeyColumns != 8)
      for(j = 1; j < uKeyColumns; j++)
        for (int k=0;k<4;k++)
          tempKey[j][k] ^= tempKey[j-1][k];
    else
    {
      for(j = 1; j < uKeyColumns/2; j++)
        for (int k=0;k<4;k++)
          tempKey[j][k] ^= tempKey[j-1][k];

      tempKey[uKeyColumns/2][0] ^= S[tempKey[uKeyColumns/2 - 1][0]];
      tempKey[uKeyColumns/2][1] ^= S[tempKey[uKeyColumns/2 - 1][1]];
      tempKey[uKeyColumns/2][2] ^= S[tempKey[uKeyColumns/2 - 1][2]];
      tempKey[uKeyColumns/2][3] ^= S[tempKey[uKeyColumns/2 - 1][3]];
      for(j = uKeyColumns/2 + 1; j < uKeyColumns; j++)
        for (int k=0;k<4;k++)
          tempKey[j][k] ^= tempKey[j-1][k];
    }
    for(j = 0; (j < uKeyColumns) && (r <= m_uRounds); )
    {
      for(; (j < uKeyColumns) && (t < 4); j++, t++)
        for (int k=0;k<4;k++)
          m_expandedKey[r][t][k] = tempKey[j][k];
      if(t == 4)
      {
        r++;
        t = 0;
      }
    }
  }
}

void Rijndael::keyEncToDec()
{
  for(int r = 1; r < m_uRounds; r++)
  {
    byte n_expandedKey[4][4];
    for (int i=0;i<4;i++)
      for (int j=0;j<4;j++)
      {
        byte *w=m_expandedKey[r][j];
        n_expandedKey[j][i]=U1[w[0]][i]^U2[w[1]][i]^U3[w[2]][i]^U4[w[3]][i];
      }
    memcpy(m_expandedKey[r],n_expandedKey,sizeof(m_expandedKey[0]));
  }
}


void Rijndael::decrypt(const byte a[16], byte b[16])
{
  int r;
  byte temp[4][4];

  Xor128((byte*)temp,(byte*)a,(byte*)m_expandedKey[m_uRounds]);

  Xor128(b,   T5[temp[0][0]],T6[temp[3][1]],T7[temp[2][2]],T8[temp[1][3]]);
  Xor128(b+4, T5[temp[1][0]],T6[temp[0][1]],T7[temp[3][2]],T8[temp[2][3]]);
  Xor128(b+8, T5[temp[2][0]],T6[temp[1][1]],T7[temp[0][2]],T8[temp[3][3]]);
  Xor128(b+12,T5[temp[3][0]],T6[temp[2][1]],T7[temp[1][2]],T8[temp[0][3]]);

  for(r = m_uRounds-1; r > 1; r--)
  {
    Xor128((byte*)temp,(byte*)b,(byte*)m_expandedKey[r]);
    Xor128(b,   T5[temp[0][0]],T6[temp[3][1]],T7[temp[2][2]],T8[temp[1][3]]);
    Xor128(b+4, T5[temp[1][0]],T6[temp[0][1]],T7[temp[3][2]],T8[temp[2][3]]);
    Xor128(b+8, T5[temp[2][0]],T6[temp[1][1]],T7[temp[0][2]],T8[temp[3][3]]);
    Xor128(b+12,T5[temp[3][0]],T6[temp[2][1]],T7[temp[1][2]],T8[temp[0][3]]);
  }

  Xor128((byte*)temp,(byte*)b,(byte*)m_expandedKey[1]);
  b[ 0] = S5[temp[0][0]];
  b[ 1] = S5[temp[3][1]];
  b[ 2] = S5[temp[2][2]];
  b[ 3] = S5[temp[1][3]];
  b[ 4] = S5[temp[1][0]];
  b[ 5] = S5[temp[0][1]];
  b[ 6] = S5[temp[3][2]];
  b[ 7] = S5[temp[2][3]];
  b[ 8] = S5[temp[2][0]];
  b[ 9] = S5[temp[1][1]];
  b[10] = S5[temp[0][2]];
  b[11] = S5[temp[3][3]];
  b[12] = S5[temp[3][0]];
  b[13] = S5[temp[2][1]];
  b[14] = S5[temp[1][2]];
  b[15] = S5[temp[0][3]];
  Xor128((byte*)b,(byte*)b,(byte*)m_expandedKey[0]);
}

#define ff_poly 0x011b
#define ff_hi   0x80

#define FFinv(x)    ((x) ? pow[255 - log[x]]: 0)

#define FFmul02(x) (x ? pow[log[x] + 0x19] : 0)
#define FFmul03(x) (x ? pow[log[x] + 0x01] : 0)
#define FFmul09(x) (x ? pow[log[x] + 0xc7] : 0)
#define FFmul0b(x) (x ? pow[log[x] + 0x68] : 0)
#define FFmul0d(x) (x ? pow[log[x] + 0xee] : 0)
#define FFmul0e(x) (x ? pow[log[x] + 0xdf] : 0)
#define fwd_affine(x) \
    (w = (uint)x, w ^= (w<<1)^(w<<2)^(w<<3)^(w<<4), (byte)(0x63^(w^(w>>8))))

#define inv_affine(x) \
    (w = (uint)x, w = (w<<1)^(w<<3)^(w<<6), (byte)(0x05^(w^(w>>8))))

void Rijndael::GenerateTables()
{
  unsigned char pow[512],log[256];
  int i = 0, w = 1;
  do
  {
    pow[i] = (byte)w;
    pow[i + 255] = (byte)w;
    log[w] = (byte)i++;
    w ^=  (w << 1) ^ (w & ff_hi ? ff_poly : 0);
  } while (w != 1);

  for (int i = 0,w = 1; i < (int)sizeof(rcon)/(int)sizeof(rcon[0]); i++)
  {
    rcon[i] = w;
    w = (w << 1) ^ (w & ff_hi ? ff_poly : 0);
  }
  for(int i = 0; i < 256; ++i)
  {
    unsigned char b=S[i]=fwd_affine(FFinv((byte)i));
    T1[i][1]=T1[i][2]=T2[i][2]=T2[i][3]=T3[i][0]=T3[i][3]=T4[i][0]=T4[i][1]=b;
    T1[i][0]=T2[i][1]=T3[i][2]=T4[i][3]=FFmul02(b);
    T1[i][3]=T2[i][0]=T3[i][1]=T4[i][2]=FFmul03(b);
    S5[i] = b = FFinv(inv_affine((byte)i));
    U1[b][3]=U2[b][0]=U3[b][1]=U4[b][2]=T5[i][3]=T6[i][0]=T7[i][1]=T8[i][2]=FFmul0b(b);
    U1[b][1]=U2[b][2]=U3[b][3]=U4[b][0]=T5[i][1]=T6[i][2]=T7[i][3]=T8[i][0]=FFmul09(b);
    U1[b][2]=U2[b][3]=U3[b][0]=U4[b][1]=T5[i][2]=T6[i][3]=T7[i][0]=T8[i][1]=FFmul0d(b);
    U1[b][0]=U2[b][1]=U3[b][2]=U4[b][3]=T5[i][0]=T6[i][1]=T7[i][2]=T8[i][3]=FFmul0e(b);
  }
}

//Rjindael.cpp end

//SHA1.cpp start
/*
SHA-1 in C
By Steve Reid <steve@edmweb.com>
100% Public Domain

Test Vectors (from FIPS PUB 180-1)
"abc"
  A9993E36 4706816A BA3E2571 7850C26C 9CD0D89D
"abcdbcdecdefdefgefghfghighijhijkijkljklmklmnlmnomnopnopq"
  84983E44 1C3BD26E BAAE4AA1 F95129E5 E54670F1
A million repetitions of "a"
  34AA973C D4C4DAA4 F61EEB2B DBAD2731 6534016F
*/

#if !defined(LITTLE_ENDIAN) && !defined(BIG_ENDIAN)
  #if defined(_M_IX86) || defined(_M_I86) || defined(__alpha)
    #define LITTLE_ENDIAN
  #else
    #error "LITTLE_ENDIAN or BIG_ENDIAN must be defined"
	#endif
#endif

/* #define SHA1HANDSOFF * Copies data before messing with it. */

#define rol(value, bits) (((value) << (bits)) | ((value) >> (32 - (bits))))

/* blk0() and blk() perform the initial expand. */
/* I got the idea of expanding during the round function from SSLeay */
#ifdef LITTLE_ENDIAN
#define blk0(i) (block->l[i] = (rol(block->l[i],24)&0xFF00FF00) \
    |(rol(block->l[i],8)&0x00FF00FF))
#else
#define blk0(i) block->l[i]
#endif
#define blk(i) (block->l[i&15] = rol(block->l[(i+13)&15]^block->l[(i+8)&15] \
    ^block->l[(i+2)&15]^block->l[i&15],1))

/* (R0+R1), R2, R3, R4 are the different operations used in SHA1 */
#define R0(v,w,x,y,z,i) {z+=((w&(x^y))^y)+blk0(i)+0x5A827999+rol(v,5);w=rol(w,30);}
#define R1(v,w,x,y,z,i) {z+=((w&(x^y))^y)+blk(i)+0x5A827999+rol(v,5);w=rol(w,30);}
#define R2(v,w,x,y,z,i) {z+=(w^x^y)+blk(i)+0x6ED9EBA1+rol(v,5);w=rol(w,30);}
#define R3(v,w,x,y,z,i) {z+=(((w|x)&y)|(w&x))+blk(i)+0x8F1BBCDC+rol(v,5);w=rol(w,30);}
#define R4(v,w,x,y,z,i) {z+=(w^x^y)+blk(i)+0xCA62C1D6+rol(v,5);w=rol(w,30);}


/* Hash a single 512-bit block. This is the core of the algorithm. */

void SHA1Transform(uint32 state[5], unsigned char buffer[64])
{
  uint32 a, b, c, d, e;
  typedef union {
    unsigned char c[64];
    uint32 l[16];
} CHAR64LONG16;
CHAR64LONG16* block;
#ifdef SHA1HANDSOFF
static unsigned char workspace[64];
    block = (CHAR64LONG16*)workspace;
    memcpy(block, buffer, 64);
#else
    block = (CHAR64LONG16*)buffer;
#endif
#ifdef SFX_MODULE
    static int pos[80][5];
    static bool pinit=false;
    if (!pinit)
    {
      for (int I=0,P=0;I<80;I++,P=(P ? P-1:4))
      {
        pos[I][0]=P;
        pos[I][1]=(P+1)%5;
        pos[I][2]=(P+2)%5;
        pos[I][3]=(P+3)%5;
        pos[I][4]=(P+4)%5;
      }
      pinit=true;
    }
    uint32 s[5];
    for (int I=0;I<sizeof(s)/sizeof(s[0]);I++)
      s[I]=state[I];

    for (int I=0;I<16;I++)
      R0(s[pos[I][0]],s[pos[I][1]],s[pos[I][2]],s[pos[I][3]],s[pos[I][4]],I);
    for (int I=16;I<20;I++)
      R1(s[pos[I][0]],s[pos[I][1]],s[pos[I][2]],s[pos[I][3]],s[pos[I][4]],I);
    for (int I=20;I<40;I++)
      R2(s[pos[I][0]],s[pos[I][1]],s[pos[I][2]],s[pos[I][3]],s[pos[I][4]],I);
    for (int I=40;I<60;I++)
      R3(s[pos[I][0]],s[pos[I][1]],s[pos[I][2]],s[pos[I][3]],s[pos[I][4]],I);
    for (int I=60;I<80;I++)
      R4(s[pos[I][0]],s[pos[I][1]],s[pos[I][2]],s[pos[I][3]],s[pos[I][4]],I);

    for (int I=0;I<sizeof(s)/sizeof(s[0]);I++)
      state[I]+=s[I];
#else
    /* Copy context->state[] to working vars */
    a = state[0];
    b = state[1];
    c = state[2];
    d = state[3];
    e = state[4];
    /* 4 rounds of 20 operations each. Loop unrolled. */
    R0(a,b,c,d,e, 0); R0(e,a,b,c,d, 1); R0(d,e,a,b,c, 2); R0(c,d,e,a,b, 3);
    R0(b,c,d,e,a, 4); R0(a,b,c,d,e, 5); R0(e,a,b,c,d, 6); R0(d,e,a,b,c, 7);
    R0(c,d,e,a,b, 8); R0(b,c,d,e,a, 9); R0(a,b,c,d,e,10); R0(e,a,b,c,d,11);
    R0(d,e,a,b,c,12); R0(c,d,e,a,b,13); R0(b,c,d,e,a,14); R0(a,b,c,d,e,15);
    R1(e,a,b,c,d,16); R1(d,e,a,b,c,17); R1(c,d,e,a,b,18); R1(b,c,d,e,a,19);
    R2(a,b,c,d,e,20); R2(e,a,b,c,d,21); R2(d,e,a,b,c,22); R2(c,d,e,a,b,23);
    R2(b,c,d,e,a,24); R2(a,b,c,d,e,25); R2(e,a,b,c,d,26); R2(d,e,a,b,c,27);
    R2(c,d,e,a,b,28); R2(b,c,d,e,a,29); R2(a,b,c,d,e,30); R2(e,a,b,c,d,31);
    R2(d,e,a,b,c,32); R2(c,d,e,a,b,33); R2(b,c,d,e,a,34); R2(a,b,c,d,e,35);
    R2(e,a,b,c,d,36); R2(d,e,a,b,c,37); R2(c,d,e,a,b,38); R2(b,c,d,e,a,39);
    R3(a,b,c,d,e,40); R3(e,a,b,c,d,41); R3(d,e,a,b,c,42); R3(c,d,e,a,b,43);
    R3(b,c,d,e,a,44); R3(a,b,c,d,e,45); R3(e,a,b,c,d,46); R3(d,e,a,b,c,47);
    R3(c,d,e,a,b,48); R3(b,c,d,e,a,49); R3(a,b,c,d,e,50); R3(e,a,b,c,d,51);
    R3(d,e,a,b,c,52); R3(c,d,e,a,b,53); R3(b,c,d,e,a,54); R3(a,b,c,d,e,55);
    R3(e,a,b,c,d,56); R3(d,e,a,b,c,57); R3(c,d,e,a,b,58); R3(b,c,d,e,a,59);
    R4(a,b,c,d,e,60); R4(e,a,b,c,d,61); R4(d,e,a,b,c,62); R4(c,d,e,a,b,63);
    R4(b,c,d,e,a,64); R4(a,b,c,d,e,65); R4(e,a,b,c,d,66); R4(d,e,a,b,c,67);
    R4(c,d,e,a,b,68); R4(b,c,d,e,a,69); R4(a,b,c,d,e,70); R4(e,a,b,c,d,71);
    R4(d,e,a,b,c,72); R4(c,d,e,a,b,73); R4(b,c,d,e,a,74); R4(a,b,c,d,e,75);
    R4(e,a,b,c,d,76); R4(d,e,a,b,c,77); R4(c,d,e,a,b,78); R4(b,c,d,e,a,79);
    /* Add the working vars back into context.state[] */
    state[0] += a;
    state[1] += b;
    state[2] += c;
    state[3] += d;
    state[4] += e;

    /* Wipe variables */
    a = b = c = d = e = 0;
    memset(&a,0,sizeof(a));
#endif
}


/* Initialize new context */

void hash_initial(hash_context* context)
{
    /* SHA1 initialization constants */
    context->state[0] = 0x67452301;
    context->state[1] = 0xEFCDAB89;
    context->state[2] = 0x98BADCFE;
    context->state[3] = 0x10325476;
    context->state[4] = 0xC3D2E1F0;
    context->count[0] = context->count[1] = 0;
}


/* Run your data through this. */
void hash_process( hash_context * context, unsigned char * data, unsigned len )
{
unsigned int i, j;
uint blen = ((uint)len)<<3;

    j = (context->count[0] >> 3) & 63;
    if ((context->count[0] += blen) < blen ) context->count[1]++;
    context->count[1] += (len >> 29);
    if ((j + len) > 63) {
        memcpy(&context->buffer[j], data, (i = 64-j));
        SHA1Transform(context->state, context->buffer);
        for ( ; i + 63 < len; i += 64) {
#ifdef ALLOW_NOT_ALIGNED_INT
            SHA1Transform(context->state, &data[i]);
#else
            unsigned char buffer[64];
            memcpy(buffer,data+i,sizeof(buffer));
            SHA1Transform(context->state, buffer);
            memcpy(data+i,buffer,sizeof(buffer));
#endif
#ifdef BIG_ENDIAN
            unsigned char *d=data+i;
            for (int k=0;k<64;k+=4)
            {
              byte b0=d[k],b1=d[k+1];
              d[k]=d[k+3];
              d[k+1]=d[k+2];
              d[k+2]=b1;
              d[k+3]=b0;
            }
#endif
        }
        j = 0;
    }
    else i = 0;
    if (len > i)
      memcpy(&context->buffer[j], &data[i], len - i);
}


/* Add padding and return the message digest. */

void hash_final( hash_context* context, uint32 digest[5] )
{
uint i, j;
unsigned char finalcount[8];

    for (i = 0; i < 8; i++) {
        finalcount[i] = (unsigned char)((context->count[(i >= 4 ? 0 : 1)]
         >> ((3-(i & 3)) * 8) ) & 255);  /* Endian independent */
    }
    unsigned char ch='\200';
    hash_process(context, &ch, 1);
    while ((context->count[0] & 504) != 448) {
        ch=0;
        hash_process(context, &ch, 1);
    }
    hash_process(context, finalcount, 8);  /* Should cause a SHA1Transform() */
    for (i = 0; i < 5; i++) {
        digest[i] = context->state[i] & 0xffffffff;
    }
    /* Wipe variables */
    memset(&i,0,sizeof(i));
    memset(&j,0,sizeof(j));
    memset(context->buffer, 0, 64);
    memset(context->state, 0, 20);
    memset(context->count, 0, 8);
    memset(&finalcount, 0, 8);
#ifdef SHA1HANDSOFF  /* make SHA1Transform overwrite it's own static vars */
    SHA1Transform(context->state, context->buffer);
#endif
}
//SHA1.cpp end

#define MAXPASSWD	128

char	*ucs2_str(char *dst, const char *src)
{
    char	*ret;

    ret = dst;
    while (*src)
      {
	*dst++ = *src++;
	*dst++ = '\0';
      }
    return ret;
}

int	ucs2_len(const char *s)
{
    return 2 * strlen(s);
}

#define PAT_ANYCHAR	'?'
#define PAT_CHARS	"0123456789""abcdefghijklmnopqrstuvwxyz"
//
/*
** Increment the char pointed to by 'c', according to PAT_CHARS.
**
** Return 1 if the increment has generated a carry, else return 0.
*/


/*
int	inc_chr(char *c, char* alphabet)
{
    if (*c >= '0' && *c < '9')
	(*c)++;
    else if (*c == '9')
	(*c) = 'a';
    else if (*c >= 'a' && *c < 'z')
	(*c)++;
    else if (*c == 'z')
      {
	(*c) = '0';
	return 1;
      }
    else
	fprintf(stderr, "Bug: c == '%c'\n", *c), exit(1);
    return 0;
}
*/




int	inc_chr(char *c, char* alphabet)
{
    int i=0;
    while((*c != alphabet[i]) && i<strlen(alphabet)-1){
        i++;
    }
    i++;
    if(i == strlen(alphabet)-1){
        i=0;
         *c = alphabet[i];
        return 1;
    }
    *c = alphabet[i];
    return 0;
    
   
    
    
}



/*
** Generate the next password, based on a pattern.
**
**   @init     if set, initialize the pwd to the 1st possible value
**   @pwd      passwd is written in this buffer
**   @pat      pattern
**
** Return 1 if a passwd has been generated or 0 if we have reached the
** end of possible passwords.
*/
int	pwd_next(int init, char *pwd, const char *pat, char* alphabet)
{
    int		i;

    if (init)
      {
	strcpy(pwd, pat);
	for (i = 0; pwd[i]; i++)
	    if (pwd[i] == PAT_ANYCHAR)
		pwd[i] = alphabet[0];
      }
    else
      {
	for (i = strlen(pwd) - 1; i >= 0; i--)
	    if (pat[i] == PAT_ANYCHAR)
		if (!inc_chr(&pwd[i],alphabet))
		    break ;
	if (i == -1)
	    return 0;
        
      }
    return 1;
}

/*
** Borrowed from sources of unrar
*/
void	gen_aes_val(unsigned char *aes_key, unsigned char *aes_iv,
	char *pwd, int plen, unsigned char *salt)
{
    unsigned char	RawPsw[plen + 8];
    int			RawLength = plen + 8;
    hash_context	c;

    memcpy(RawPsw, pwd, plen);
    memcpy(RawPsw + plen, salt, 8);

    hash_initial(&c);
    const int HashRounds=0x40000;
    for (int I=0;I<HashRounds;I++)
    {
      hash_process( &c, RawPsw, RawLength);
      byte PswNum[3];
      PswNum[0]=(byte)I;
      PswNum[1]=(byte)(I>>8);
      PswNum[2]=(byte)(I>>16);
      hash_process( &c, PswNum, 3);
      if (I%(HashRounds/16)==0)
      {
        hash_context tempc=c;
        uint32 digest[5];
        hash_final( &tempc, digest);
        aes_iv[I/(HashRounds/16)]=(byte)digest[4];
      }
    }
    uint32 digest[5];
    hash_final( &c, digest);
    for (int I=0;I<4;I++)
      for (int J=0;J<4;J++)
        aes_key[I*4+J]=(byte)(digest[I]>>(J*8));
}

/*
** Test a password, using the end-of-archive block.
**
**   @salt point to 8 bytes of salt data
**   @in is a 16-byte encrypted block representing the end-of-archive block
**   @p is a password to try
**
** Return true on good passwd, false on bad passwd.
*/
int	test_password(unsigned char *salt, unsigned char *in, const char *p)
{
    unsigned char	aes_key[16];
    unsigned char	aes_iv[16];
    Rijndael		rin;
    unsigned char	out[16];
    char		p2[MAXPASSWD];

    gen_aes_val(aes_key, aes_iv, ucs2_str(p2, p), ucs2_len(p), salt);
    rin.init(Rijndael::Decrypt, aes_key, aes_iv);
    rin.blockDecrypt(in, 16, out);
    /*
    printf("---------------\n");
    printf("salt: %02x\n", (unsigned char)*salt);
    printf("key: %02x\n", (unsigned char)*aes_key);
    printf("iv: %02x\n", (unsigned char)*aes_iv);
    printf("in: %02x\n", (unsigned char)*in);
    printf("out: %02x\n", (unsigned char)*out);
    printf("---------------\n\n");
    */

	/* cmp with the usual end-of-archive block present in (all?) rar files */
    return !memcmp(out, "\xc4\x3d\x7b\x00\x40\x07\x00", 7);

}

void	disp_stats(int i_cur, int end)
{
    static int			i_old = -1;
    static struct timeval	tv0;
    static struct timeval	tv1;
    double			f;

    if (!i_cur)
      {
	if (-1 == gettimeofday(&tv0, NULL))
	    perror("gettimeofday"), exit(1);
	f = 0;
      }
    else
      {
	if (-1 == gettimeofday(&tv1, NULL))
	    perror("gettimeofday"), exit(1);
	f =
	    ((double)tv1.tv_sec + (double)tv1.tv_usec / 1.e6) -
	    ((double)tv0.tv_sec + (double)tv0.tv_usec / 1.e6);
	tv0 = tv1;
	f = (i_cur - i_old) / f;
      }
    fprintf(stderr, "\r%d pwds, %.2f pwd/sec ", i_cur, f);
    if (end)
	fprintf(stderr, "\n");
    i_old = i_cur;
}

void	found_it(const char *pwd)
{
    int	i;

    printf("| passwd is: %s\n\n", pwd);
    /*
    for (i = 0; i < (int)strlen(pwd); i++)
	printf(" %02x", (unsigned char)pwd[i]);
    printf(")\n");
    */
    exit(0);
}

int	loop_passwords(unsigned char *salt, unsigned char *in, const char *pat, int world_rank, unsigned long long int subKeyRange, char* alphabet, int world_size, unsigned long long int maxPossibleKeyCount)
{
    char	*p;
    int		i;
    int		first;
    unsigned long long int passwordCount;
    //printf("I am: %d My subKeyRange: %lld\n",world_rank, subKeyRange);
    if (!(p = (char *)malloc(strlen(pat) + 1)))
	perror("malloc"), exit(1);
    p[0] = 0;
    i = 0;
    first = 1;
  for(passwordCount = 0; passwordCount < subKeyRange*world_rank ; passwordCount++){
    pwd_next(first, p, pat, alphabet);
    //printf("I am: %d I passed: %s\n",world_rank, p);
    first = 0;
  }
    passwordCount = 0;
    if(world_rank == world_size-1){
        subKeyRange += maxPossibleKeyCount % world_size;
    }
  
    while ((pwd_next(first, p, pat, alphabet)) && (passwordCount < subKeyRange+1))
      {
      first = 0;
      if(world_rank == 0){
        if (!(i % 100)){
            fprintf(stdout, "\n %lld / %lld\n", passwordCount*world_size, maxPossibleKeyCount);
            fflush(stdout);
        }
      }
      
         // disp_stats(i, 0);
      //fprintf(stdout, "\ntrying pwd: %s\n", p);
        fflush(stdout);
      i++;
      if (test_password(salt, in, p))
        {
          //disp_stats(i, 0);
          found_it(p);
          return 1;
        }
      passwordCount++;
      }
    //printf("I am %d, I couldn't find password. Password count: %lld\n", world_rank, passwordCount);
    //fflush(stdout);
    //disp_stats(i, 1);
    free(p);
    return 0;
}

int	do_file(const char *f, const char *pat, int world_rank, unsigned long long int subKeyRange, char* alphabet, int world_size, unsigned long long int maxPossibleKeyCount )
{
    unsigned char	buf[24];
    int		fd;
    int		n;

    if (-1 == (fd = open(f, O_RDONLY, 0)))
	perror(f), exit(1);

    //fd = open("main.cpp", 0_RDONLY, 0);
    if (-1 == lseek(fd, -24, SEEK_END))
	perror("lseek"), exit(1);
    n = read(fd, buf, sizeof (buf));
    if (!n)
	fprintf(stderr, "Premature end-of-file\n"), exit(1);
    if (n != sizeof (buf))
	fprintf(stderr, "Read only %d bytes instead of %d\n",
		n, (int)sizeof (buf)), exit(1);
    close(fd);
    return(loop_passwords(buf, buf + 8, pat, world_rank, subKeyRange, alphabet, world_size, maxPossibleKeyCount));
}

void	usage(void)
{
    fprintf(stderr,
	    "Usage: unrarhp {file} {pattern}\n"
	    "  {file}     rar file to crack\n"
	    "  {pattern}  passwd pattern to test ('%c' for unknown char)\n"
	    , PAT_ANYCHAR
	   );
}



int main(int argc, char** argv) {
  
  int i;
  
  int keyNumber=0;
  int keyLength;
  unsigned long long int maxPossibleKeyCount;
  unsigned long long int subKeyStartValue;
  unsigned long long int subKeyRange;
  char* numbers = "0123456789";
  char* lowercaseLetters = "abcdefghijklmnopqrstuvwxyz";
  char* uppercaseLetters = "ABCDEFGHIJKLMONPQRSTUVWXYZ";
  char* specialLetters = "?*#.,!+-_";
  char* preferences;
  char* alphabet = (char*)malloc(sizeof(char));
  
  char	*pattern;
  const char	*file;
  file = argv[1];
  pattern = argv[2];
  preferences = argv[3];
  char* fileName;
  char* patternString;
  int fileNameLength;
  int alphabetLength=0;
    
    
    double seconds;
    time_t started=time(NULL);
 
  MPI_Init(&argc, &argv);
  int world_rank;
  MPI_Comm_rank(MPI_COMM_WORLD, &world_rank);
  int world_size;
  MPI_Comm_size(MPI_COMM_WORLD, &world_size);
    int j=0;
    int k=0;
    
  
  // Get the name of the processor
  char processor_name[MPI_MAX_PROCESSOR_NAME];
  int name_len;
  MPI_Get_processor_name(processor_name, &name_len);
  /*
   if(world_rank == 0){
    printf("Password length: ");
    fflush(stdout);
    scanf("%d",&keyLength);
    
   }
   */
   
    
  MPI_Barrier(MPI_COMM_WORLD);
   
        
        while(preferences[j] != '\0'){
            //printf("\npreferences != 0\n");
            if(preferences[j] == 'a'){
                //printf("\npreferences == a, strlen(lowercaseLetters) = %ld\n", strlen(lowercaseLetters));
                alphabet = (char*)realloc(alphabet, ((strlen(alphabet) + 26) * sizeof(char)));
                for(k=0 ; k<strlen(lowercaseLetters) ; k++){
                    alphabet[alphabetLength + k] = lowercaseLetters[k];
                }
                alphabetLength += strlen(lowercaseLetters);
            }else if(preferences[j] == 'A'){
                //printf("\npreferences == A, strlen(uppercaseLetters) = %ld\n", strlen(uppercaseLetters));
                alphabet = (char*)realloc(alphabet, ((strlen(alphabet) + 26) * sizeof(char)));
                for(k=0 ; k<strlen(uppercaseLetters) ; k++){
                    alphabet[alphabetLength + k] = uppercaseLetters[k];
                }
                alphabetLength += strlen(uppercaseLetters);
            }else if(preferences[j] == '0'){
                //printf("\npreferences == 0, strlen(numbers) = %ld\n", strlen(numbers));
                alphabet = (char*)realloc(alphabet, ((strlen(alphabet) + 10) * sizeof(char)));
                for(k=0 ; k<strlen(numbers) ; k++){
                    alphabet[alphabetLength + k] = numbers[k];
                }
                alphabetLength += strlen(numbers);
            }
            else if(preferences[j] == '*'){
                //printf("\npreferences == *, strlen(specialLetters) = %ld\n", strlen(specialLetters));
                alphabet = (char*)realloc(alphabet, ((strlen(alphabet) + strlen(specialLetters)) * sizeof(char)));
                for(k=0 ; k<strlen(specialLetters) ; k++){
                    alphabet[alphabetLength + k] = specialLetters[k];
                }
                alphabetLength += strlen(specialLetters);
            }
            //printf("\nAlphabet Length = %d\n", alphabetLength);
            //printf("\nAlphabet Length = %d strlen(alphabet) = %ld \n", alphabetLength, strlen(alphabet));
            j++;
        }
    
    MPI_Barrier(MPI_COMM_WORLD);
    
    if(world_rank == 0){
        printf("Alphabet is : %s\n", alphabet);
    }
    
    
  
  if((world_rank == 0) && (pattern[0] != 'n')){
    keyLength= strlen(pattern);
    fprintf(stdout,"keyLength : %d\n ", keyLength);
    fprintf(stdout,"world_size : %d\n ", world_size);
    fprintf(stdout,"alphabetLength : %d\n ", alphabetLength);
    maxPossibleKeyCount = alphabetLength;
    
    for(i=0;i<keyLength-1;i++){
      maxPossibleKeyCount = maxPossibleKeyCount * alphabetLength;
    }
    fprintf(stdout,"%lld possible keys\n ", maxPossibleKeyCount);
    fflush(stdout);
  }
  
  MPI_Barrier(MPI_COMM_WORLD);
  
  subKeyRange = maxPossibleKeyCount/world_size;
 
  
  if (world_rank == 0) {
    for(i=0;i<world_size;i++){
      subKeyStartValue = i*(maxPossibleKeyCount/world_size);
      
      MPI_Send(&subKeyRange, 1, MPI_UNSIGNED_LONG_LONG, i, 0, MPI_COMM_WORLD);
	  MPI_Send(&keyLength, 1, MPI_INT, i, 0, MPI_COMM_WORLD);
	  MPI_Send(&maxPossibleKeyCount, 1, MPI_UNSIGNED_LONG_LONG, i, 0, MPI_COMM_WORLD);
    }
  }
  
  MPI_Recv(&subKeyRange, 1, MPI_UNSIGNED_LONG_LONG, 0, 0, MPI_COMM_WORLD, MPI_STATUS_IGNORE);
  MPI_Recv(&keyLength, 1, MPI_INT, 0, 0, MPI_COMM_WORLD, MPI_STATUS_IGNORE);
  MPI_Recv(&maxPossibleKeyCount, 1, MPI_UNSIGNED_LONG_LONG, 0, 0, MPI_COMM_WORLD, MPI_STATUS_IGNORE);
  //printf("%d File name is: %s",world_rank, file);
  if(world_rank == world_size-1){
      /*
      printf("%lld,\n",(100 % 8));
      printf("%lld,\n",(maxPossibleKeyCount));
      printf("%lld,\n",(world_size));
      printf("%lld,\n",(maxPossibleKeyCount % longWorldSize));
      printf("%lld,\n",(subKeyRange*(world_rank+1)));
      printf("%lld,\n",(subKeyRange*(world_rank+1)) + (maxPossibleKeyCount % longWorldSize));
      */
          printf("Special: Process %d will try passwords %lld - %lld\n", world_rank, subKeyRange*world_rank, ((subKeyRange*(world_rank+1)) + (maxPossibleKeyCount % world_size) - 1));
      }else{
        printf("Process %d will try passwords %lld - %lld\n", world_rank, subKeyRange*world_rank, subKeyRange*(world_rank+1)-1);
      }
  //pattern = "?";
    //get file info
    
    int loopCount=1;
    if(strcmp(pattern, "n") == 0){
        if(world_rank == 0){
        printf("\nTrying all patterns...\n");
        }
        while(1){
            
        for(i=0 ; i<loopCount; i++){
            pattern[i] = '?';
        }
        pattern[i] = '\0';
        if(world_rank == 0){
        printf("\nTrying length %d...\n", i);
        }
        
        if(world_rank == 0){
        keyLength= strlen(pattern);
        
        if(world_rank == 0){
        fprintf(stdout,"keyLength : %d\n ", keyLength);
        fprintf(stdout,"world_size : %d\n ", world_size);
        fprintf(stdout,"alphabetLength : %d\n ", alphabetLength);
        maxPossibleKeyCount = alphabetLength;

        for(i=0;i<keyLength-1;i++){
          maxPossibleKeyCount = maxPossibleKeyCount * alphabetLength;
         }
        fprintf(stdout,"%lld possible keys\n ", maxPossibleKeyCount);
        fflush(stdout);
        }
        
        }

          MPI_Barrier(MPI_COMM_WORLD);

          subKeyRange = maxPossibleKeyCount/world_size;


          if (world_rank == 0) {
            for(i=0;i<world_size;i++){
              subKeyStartValue = i*(maxPossibleKeyCount/world_size);

              MPI_Send(&subKeyRange, 1, MPI_UNSIGNED_LONG_LONG, i, 0, MPI_COMM_WORLD);
              MPI_Send(&keyLength, 1, MPI_INT, i, 0, MPI_COMM_WORLD);
              //MPI_Send(fileName, fileNameLength+5, MPI_CHAR, i, 0, MPI_COMM_WORLD);
            }
          }

      MPI_Recv(&subKeyRange, 1, MPI_UNSIGNED_LONG_LONG, 0, 0, MPI_COMM_WORLD, MPI_STATUS_IGNORE);
      MPI_Recv(&keyLength, 1, MPI_INT, 0, 0, MPI_COMM_WORLD, MPI_STATUS_IGNORE);
      //MPI_Recv(fileName, fileNameLength, MPI_CHAR, 0, 0, MPI_COMM_WORLD, MPI_STATUS_IGNORE);
      //printf("%d File name is: %s",world_rank, file);
      if(world_rank == world_size){
          printf("Special: Process %d will try passwords %lld - %lld\n", world_rank, subKeyRange*world_rank, (subKeyRange*(world_rank+1) + (maxPossibleKeyCount % world_size) - 1));
      }else{
        printf("Process %d will try passwords %lld - %lld\n", world_rank, subKeyRange*world_rank, subKeyRange*(world_rank+1)-1);
      }
      
      
      do_file(file, pattern, world_rank, subKeyRange, alphabet, world_size, maxPossibleKeyCount);
            loopCount++;
        }
    }
    do_file(file, pattern, world_rank, subKeyRange, alphabet, world_size, maxPossibleKeyCount);
    fflush(stdout); 
    
     

  MPI_Barrier(MPI_COMM_WORLD);
  MPI_Finalize();
}


