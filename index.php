<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tracks Indexed</title>
</head>

<style>
  li {
    margin: 10px 0;
    padding: 20px;
    border: 2px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    background-color: #f7f7f7;
    overflow: hidden;
  }

  .description, .artist{
    font-size: 1em;
    font-weight: bold;
    display: inline-block;
  }

  .timestamp {
    font-size: 14px;
    color: #888;
    margin-bottom: 10px;
    display: inline-block;
  }

  .link {
    display: inline-block;
    padding: 5px 10px;
    margin: 10px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
  }

  .link:hover {
    background-color: #0062cc;
  }

  /* Search container */
  .search-container {
    margin-top: 10px;
    margin-bottom: 10px;
    position: relative;
    width: 100%;
  }

  /* Search input */
  .search-container input[type="text"] {
    padding: 10px;
    width: 100%;
    border: none;
    border: 4px solid #ccc;
    font-size: 16px;
  }
</style>

<?php
?>

<body>
  <div class="search-container">
    <input type="text" placeholder="Search..." oninput="searchForTrack(this.value)">
  </div>

  <ol id='tracksOl'>
    <?php for($i=1;$i<$linesCount;$i++)printLiItem($lines[$i]);?>
  </ol>
</body>

<script type="text/javascript" src="./allShabads.js"></script>
<script>
  const allTracksElem = document.getElementsByClassName('eachTrack')
  const allTracksData = []

  function getTrackTitle(link){return link.replace(/%20/g, ' ').split('/').splice(-1)}

  function putShabadForShabadIds(){
    const ptags = document.getElementsByClassName('shabadDisplay')
    for(const tag of ptags){
      let shabadId = tag.getAttribute('shabadid')
      shabadId = shabadId.slice(0,-1) //remove the \n key
      tag.innerText = ""
      for(const line of allShabadsAdi[shabadId]){
        tag.innerText += line+"\n"
      }
    }

    const atags = document.getElementsByClassName('link')
    for(const tag of atags){
      tag.innerText = getTrackTitle(tag.getAttribute('href'))
    }
  } 

  function getTracksData(){
    for(const item of allTracksElem){
      const data={
        description: item.getElementsByClassName('description')[0].innerText,
        timestamp: item.getElementsByClassName('timestamp')[0].innerText,
        link: item.getElementsByClassName('link')[0].getAttribute('href'),
        artist: item.getElementsByClassName('artist')[0].innerText
      }
      const shabadElem = item.getElementsByClassName('shabad')
      if (shabadElem.length>0){
        data.shabadId = item.getElementsByClassName('shabadDisplay')[0].getAttribute('shabadId')
      }
      allTracksData.push(data)
    }
  }

  function displayData(objLst){
    const ol = document.getElementById('tracksOl')
    ol.innerHTML = '';
    for(const obj of objLst){
      const li = document.createElement('li')
      const div = document.createElement('div')
      div.setAttribute('class','eachTrack')

      const divDesc = document.createElement('div')
      const pDesc = document.createElement('p')
      pDesc.setAttribute('class','description')
      pDesc.innerText = obj.description
      divDesc.appendChild(pDesc)
      div.appendChild(divDesc)

      const divArt = document.createElement('div')
      const pArt = document.createElement('p')
      pArt.setAttribute('class','artist')
      pArt.innerText = obj.artist
      divArt.appendChild(pArt)
      div.appendChild(divArt)

      const divTime = document.createElement('div')
      const pTime = document.createElement('p')
      pTime.setAttribute('class','timestamp')
      pTime.innerText = obj.timestamp
      divTime.appendChild(pTime)
      div.appendChild(divTime)

      const divLink = document.createElement('div')
      const pLink = document.createElement('p')
      pLink.setAttribute('class','link')
      pLink.innerText = getTrackTitle(obj.link)
      divLink.appendChild(pLink)
      div.appendChild(divLink)

      li.appendChild(div)
      ol.appendChild(li)
    }
  }

  const ol = document.getElementById("tracksOl");
  function searchForTrack(e){
    if (e===""){

    }
    const wordsEntered = e.toLowerCase().split(' ')
    const results = allTracksData.filter(obj => {
      const values = Object.values(obj);
      for(const word of wordsEntered){
        const wordInTrackObj = values.some(line => line.toLowerCase().includes(word));
        if(!wordInTrackObj){
          return false;
        }
      }
      console.log(values)
      return true;
    });
    displayData(results)
    console.log(results)
  }

  putShabadForShabadIds()
  getTracksData()

</script>

</html>
