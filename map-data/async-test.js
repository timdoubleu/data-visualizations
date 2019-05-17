function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

async function demo() {
  console.log('Taking a break...');
  await sleep(2000);
  console.log('Two seconds later');

  for (var i = 0; i < 10; i++) {
     console.log("Crawling: "+ url);
     await sleep(2000);
  }
}

demo();











async function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}


async function mainLoop() {
  for (var i = 0; i < 10; i++) {
     console.log("Crawling: "+ url);
     await sleep(5000);
  }
}

mainLoop();