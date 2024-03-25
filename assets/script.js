const ALL_TRACKS_DATA = []
let SHOW_TRACKS_DATA = []

function add_shabad_from_user_input() {
  const input_tag = document.getElementById('usedShabadId')
  const user_input = input_tag.value
  const list_opts = document.getElementById('shabadId_list_opts')
  list_opts.innerHTML = ''
  if (user_input === '') return

  let max_items_to_show = 10
  const keyObj = findShabadsKey(user_input)
  for (let key in keyObj) {
    const opt = document.createElement('p')
    opt.classList.add('shabad_opt_from_userinput')
    opt.onclick = () => {
      list_opts.innerHTML = ''
      input_tag.value = key
      document.getElementById('theShabadSelected').textContent = keyObj[key]
    }
    // opt.value = key
    opt.innerText = keyObj[key]
    list_opts.appendChild(opt)
    max_items_to_show -= 1
    if (max_items_to_show < 0) break
  }
}

function findShabadsKey(searchInput) {
  const all_matched_shabad_keys = {}
  for (const key in ALL_SHABADS) {
    const shabadArray = ALL_SHABADS[key]

    for (const line of shabadArray) {
      const wordsArray = line.split(' ')

      let line_matched = true
      for (let i = 0; i < searchInput.length; i++) {
        if (!line_matched) break
        if (
          wordsArray.length === i ||
          wordsArray[i][0].toLowerCase() !== searchInput[i].toLowerCase()
        ) {
          line_matched = false
        }
      }

      if (line_matched) {
        all_matched_shabad_keys[key] = line
        break
      }
    }
  }
  return all_matched_shabad_keys
}

function scrollToDivAccodingToUrl() {
  const urlParams = new URLSearchParams(window.location.search)
  const theId = urlParams.get('id')
  if (!theId) return
  document.getElementById(theId).getElementsByTagName('button')[0].click()
  // document.getElementById(theId).scrollIntoView();
}

function trackNodeBtnClicked(obj_id) {
  const obj = ALL_TRACKS_DATA.find((obj) => obj.id == obj_id)
  const current_playing_audio = document.getElementById('current_playing_audio')
  current_playing_audio.style.display = 'block'
  document.getElementById('DescOfTrack').innerText =
    'Description: ' + obj.description
  document.getElementById('playingArtist').innerText = 'Keertani: ' + obj.artist
  document.getElementById('trackAdded').innerText =
    'Added: ' + getFormatedTime(obj.created)

  document.getElementById('timeStampOfDecs').innerText = obj.timestamp
  document.getElementById('TrackTitle').innerText = getTrackTitle(obj.link)
  document.getElementById('TrackTitle').href = obj.link

  document.getElementsByTagName('audio')[0].src = obj.link

  document.getElementById('timeStampOfDecs').onclick = function() {
    const timeLst = obj.timestamp.split(':')
    let totalSeconds = 0
    let multiplier = 1
    for (let i = timeLst.length - 1; i > -1; i--) {
      totalSeconds += multiplier * parseInt(timeLst[i])
      multiplier *= 60
    }
    document.getElementsByTagName('audio')[0].currentTime = totalSeconds
  }

  document.getElementById('copyLink').onclick = function() {
    const url = new URL(window.location.href.split('?')[0].split('#')[0])
    url.searchParams.append('id', obj_id)
    const copyText = url.href
    if (navigator.clipboard) {
      navigator.clipboard
        .writeText(copyText)
        .then(() => alert('Text Copied'))
        .catch((err) => {
          console.error('Failed to copy text: ', err)
        })
    } else {
      const textArea = document.createElement('textarea')
      textArea.value = copyText
      document.body.appendChild(textArea)
      textArea.select()
      document.execCommand('copy')
      document.body.removeChild(textArea)
      alert('Text Copied (Fallback)')
    }
  }

  const details = document.getElementById('trackShabadIdDetails')
  if (obj.shabadID != '' && ALL_SHABADS[obj.shabadID]) {
    const summary = document.createElement('summary')
    summary.innerText = `Shabad ID: ${obj.shabadID}`
    details.innerText = ALL_SHABADS[obj.shabadID].join('\n')
    details.appendChild(summary)
    details.style.display = 'block'
  } else {
    details.style.display = 'none'
  }

  document.getElementById('timeStampOfDecs').click() // to start playing
  // document.getElementById('current_playing_audio').scrollIntoView()
}

function getFormatedTime(unixTimeStamp) {
  const the_date = new Date(unixTimeStamp * 1000)
  const and = the_date.toLocaleString()
  return and
}

function displayData(alreadyFiltered = false) {
  const ol = document.getElementsByTagName('ol')[0]
  ol.innerHTML = ''

  if (!alreadyFiltered) filterDataByChosenOpt()

  const len = SHOW_TRACKS_DATA.length
  document.getElementById('trackInfo').innerText = `Found ${len} Tracks`

  for (let i = len - 1; i > -1; i--) {
    const obj = SHOW_TRACKS_DATA[i]

    const li = document.createElement('li')
    li.setAttribute('class', 'trackNode')
    li.setAttribute('id', obj.id)
    li.innerHTML = `
        <div class="topBarLine">
          <div class="nodeNum"># ${obj.id}</div>
          <div class="description">${obj.description}</div>
        </div>
        <div class="middleDetail">
          <div class="artist">Keertani: ${obj.artist}</div>
          <div class="timestamp">Time Stamp: ${obj.timestamp}</div>
          <div class="timeadded">Added: ${getFormatedTime(obj.created)}</div>
        </div>
        <button class="playTrackBtnWithTitle" onclick="trackNodeBtnClicked(${obj.id})">
          ${getTrackTitle(obj.link)}
        </button>
      `
    if (obj.shabadID != '' && ALL_SHABADS[obj.shabadID]) {
      li.innerHTML += `
        <details>
          <summary>Shabad ID: ${obj.shabadID}</summary>
          ${ALL_SHABADS[obj.shabadID].join('<br>')}
        </details>`
    }
    ol.appendChild(li)
  }
}

function searchForTrack(e) {
  const wordsEntered = e.toLowerCase().split(' ')

  filterDataByChosenOpt()

  SHOW_TRACKS_DATA = SHOW_TRACKS_DATA.filter((obj) => {
    const values = Object.values(obj)
    if (obj.shabadID != '' && ALL_SHABADS[obj.shabadID]) {
      values.push(ALL_SHABADS[obj.shabadID].join('\n'))
    }

    for (const word of wordsEntered) {
      const wordInTrackObj = values.some((line) =>
        line.toLowerCase().includes(word),
      )
      if (!wordInTrackObj) {
        return false
      }
    }
    return true
  })
  displayData(true)
}

function putOptsInSelect() {
  const allKeertanis = []
  const allTypes = []
  ALL_TRACKS_DATA.forEach((track) => {
    if (!allKeertanis.includes(track.artist)) {
      allKeertanis.push(track.artist)
    }

    if (!allTypes.includes(track.type)) {
      allTypes.push(track.type)
    }
  })

  const last_picked_keertani = localStorage.getItem('TrackIndex: keertani')
  const selectKeertani = document.getElementById('pickKeertani')
  for (const keertani of allKeertanis) {
    const opt = document.createElement('option')
    opt.setAttribute('value', keertani)
    opt.innerText = keertani
    if (keertani === last_picked_keertani) {
      opt.selected = true
    }
    selectKeertani.appendChild(opt)
  }

  const last_picked_type = localStorage.getItem('TrackIndex: TrackType')
  const selectType = document.getElementById('pickTrackType')
  for (const typee of allTypes) {
    const opt = document.createElement('option')
    opt.setAttribute('value', typee)
    opt.innerText = typee
    if (typee === last_picked_type) {
      opt.selected = true
    }
    selectType.appendChild(opt)
  }
}

function filter_keertani_from_url() {
  const urlParams = new URLSearchParams(window.location.search)
  const artist = urlParams.get('artist')
  if (!artist) return
  const select = document.getElementById('pickKeertani')
  for (const opt of select.options) {
    if (opt.innerText == artist) {
      opt.selected = true
      break
    }
  }
  filterDataByChosenOpt()
}

function filterDataByChosenOpt() {
  const keertaniOpt = document.getElementById('pickKeertani').value
  localStorage.setItem('TrackIndex: keertani', keertaniOpt)

  const typeOpt = document.getElementById('pickTrackType').value
  localStorage.setItem('TrackIndex: TrackType', typeOpt)

  if (keertaniOpt === 'All' && typeOpt === 'All') {
    SHOW_TRACKS_DATA = ALL_TRACKS_DATA
  } else {
    SHOW_TRACKS_DATA = ALL_TRACKS_DATA.filter(
      (item) =>
        (typeOpt === item.type || typeOpt === 'All') &&
        (keertaniOpt === item.artist || keertaniOpt === 'All'),
    )
  }
}

function getTrackTitle(link) {
  return decodeURIComponent(link.replace(/%20/g, ' ').split('/').splice(-1))
}

function showShabads() {
  const detailsElements = document.querySelectorAll('details')
  for (let i = 1; i < detailsElements.length; i++) {
    const details = detailsElements[i]
    details.open = !details.open
  }
}

function toggle_descend_order() {
  SHOW_TRACKS_DATA.reverse()
  displayData(true)
  const btn = document.getElementById('descend_order_btn')
  console.log(btn.innerText)
  btn.innerText =
    SHOW_TRACKS_DATA[0].id > SHOW_TRACKS_DATA[1].id
      ? 'Ascending Order'
      : 'Descending Order'
}

fetch('http://45.76.2.28/trackIndex/util/getData.php')
  .then((res) => {
    if (!res.ok) throw new Error('Something went wrong!')
    return res.json()
  })
  .then((data) => {
    for (let i = 0; i < data.length; i++) {
      if (i===0) continue
      const obj = data[i]
      obj.id = `${i + 1}`
      ALL_TRACKS_DATA.push(obj)
    }
    SHOW_TRACKS_DATA = ALL_TRACKS_DATA
    putOptsInSelect()
    filter_keertani_from_url()
    displayData(true)
    scrollToDivAccodingToUrl()
  })
  .catch((err) => {
    console.log(err)
  })
