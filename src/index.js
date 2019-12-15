const messages = document.querySelector('.messages');
const usersTable = document.querySelector('.users_table');
let originTarget;
let originText = '';
const messUrl = './API/handle_message.php';
const userUrl = './API/handle_user.php';


const getCookie = cname => {
  const name = cname.concat('=');
  const decodedCookie = decodeURIComponent(document.cookie);
  const ca = decodedCookie.split(';');
  let cookie;
  return ca.some(el => {
    cookie = el;
    while (cookie.charAt(0) === ' ') {
      cookie = cookie.substring(1);
    }
    if (cookie.indexOf(name) !== 0) return false;
    cookie = cookie.substring(name.length, cookie.length);
    return true;
  })
    ? cookie : '';
};

const userId = Number(getCookie('user_id'));
const permission = getCookie('permission');

const toggleShowBtn = div => {
  $(div).find('.message__edite:first').toggleClass('hidden');
};

const printAddCommentFram = target => {
  toggleShowBtn($(target).closest('.message_edite_wrapper'));
  $(target).closest('.card-body').append(`
    <section class="comment_board">
      <form method='POST' action ='handle_add_child.php'>
        <div class='comment_board_input'>
          <input type='hidden' name='parentId' value=${target.dataset.id} >
          <textarea class='w-100' name='content' rows='5' placeholder='What do you want to say ?' required></textarea>
        </div>
        <button type='submit' class='add_sub_commit_btn btn btn-outline-secondary'>Send</button>
      </form>
    </section>
  `);
};

const changeEditeFrame = (type, e) => {
  const showEditeFrame = btn => {
    const printEditeUserForm = (data, selector, text) => {
      $(data).find(selector[1]).html(`
      <form class='edite__user' method='POST' action='handle_edite_user.php?id=${btn.dataset.id}'>
        <input type="hidden" name='id' value=${btn.dataset.id} />
        <select name='permissionOption'>
          <option ${(text === 'normal') ? 'selected' : ''} value='normal'>normal</option>
          <option ${(text === 'admin') ? 'selected' : ''} value='admin'>admin</option>
        </select>
        <button type='submit' class='btn edite__send_btn icon ' ></button>
      </form>
      `);
    };

    const printEditeMessForm = (node, selector, text) => {
      const newNode = node;
      newNode.querySelector(selector[1]).outerHTML = `
      <form method='POST' action='handle_edite.php?id=${btn.dataset.id}'>
        <input type='hidden' name='id' value=${btn.dataset.id}>
        <textarea name='content' rows='5' class='w-100 edite_comment__board' required>${text}</textarea>
        <button type="button" title="或按 ESC" class="cancel_btn btn btn-outline-secondary">取消</button>
        <button type='submit' class='btn edite__send_btn icon' ></button>
      </form>`;
    };

    const selector = (type === 'user') ? ['.user_data', '.permission__th'] : ['.message', '.message__content'];
    const data = btn.closest(selector[0]);
    toggleShowBtn(data);
    const text = $(data).find(selector[1]).html();
    if (type === 'user') {
      printEditeUserForm(data, selector, text);
    } else {
      printEditeMessForm(data, selector, text);
    }
    return text;
  };

  const reinstate = () => {
    const reverseShowEditeFrame = text => {
      const selector = (type === 'user') ? ['.user_data', '.permission__th'] : ['.message', 'form'];
      const data = originTarget.closest(selector[0]);
      toggleShowBtn(data);
      if (type === 'user') $(data).find(selector[1]).html(text);
      // data.querySelector(selector[1]).innerHTML = `${text}`;
      else data.querySelector(selector[1]).outerHTML = `<p class='card-text message__content'>${text}</p>`;
    };

    const reverseShowAddFrame = () => {
      const board = originTarget.closest('.message').querySelector('.comment_board');
      board.outerHTML = '';
      toggleShowBtn(originTarget.closest('.message_edite_wrapper'));
    };

    if (!originTarget) return null;
    if ($(originTarget).hasClass('edite_btn')) return reverseShowEditeFrame(originText);
    if ($(originTarget).hasClass('add_btn')) return reverseShowAddFrame();
    return null;
  };

  if ($(e.target).hasClass('edite_btn')) {
    reinstate();
    originTarget = e.target;
    originText = showEditeFrame(e.target);
  } else if ($(e.target).hasClass('add_btn')) {
    reinstate();
    originTarget = e.target;
    printAddCommentFram(e.target);
    originText = '';
  } else if ($(e.target).hasClass('cancel_btn')) {
    reinstate();
    originTarget = null;
  }
  window.addEventListener('keydown',
    el => {
      if (el.keyCode === 27) {
        reinstate();
        originTarget = null;
      }
    });
};

const whenError = obj => {
  console.log('error', obj);
  alert('發生錯誤: ', obj.status);
};

const showAlert = (message, type) => {
  const div = type === 'fewtime' ? `
  <div id="process" class="alert alert-dismissible alert-primary">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong>${message}</strong>
</div>
  ` : `
  <div class="alert alert-dismissible alert-info">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong>${message}</strong>
</div>
  `;
  if (type === 'fewtime') $('body').prepend(div);
  else $('#notation').prepend(div);
};

const hiddenAlert = () => {
  $('#process').remove();
};

const reRenderMessages = () => {
  const createEditeSectionHtml = data => {
    if (data.user_id !== userId && permission !== 'admin') return '';
    return `
    <div class="message__edite">
    <button class="edite_btn btn icon" title="edite" data-id="${data.id}"></button>
    <button title="delete" class="btn delete_btn icon" data-id="${data.id}" data-type="comment"></button>
    </div>`;
  };

  const createChildMessHtml = (nthData, parentId) => {
    let str = '';
    if (!nthData.sub) return str;
    nthData.sub.forEach(e => {
      const sameAuthor = e.user_id === parentId ? 'author' : '';
      str += `
      <div class="card bg-light w-75 child_message message ${sameAuthor}">
      <div class="card-body">
      <h5 class="card-title message__nickname">${e.nickname}</h5>
      <h6 class="card-subtitle mb-2 text-muted message__time">${e.created_at}</h6>
      <p class='message__content card-text'>${e.content}</p>
      ${createEditeSectionHtml(e)}
      </div>
      </div>`;
    });
    return str;
  };

  const replaceMessages = data => {
    $('.messages > .message').each((index, el) => {
      const str = `
      <div class='card-body'>
        <h4 class="card-title message__nickname">${data[index].main.nickname}</h4>
        <h5 class="card-subtitle mb-2 text-muted message__time">${data[index].main.created_at}</h5>
      <p class='card-text message__content'>${data[index].main.content}</p>
      ${createEditeSectionHtml(data[index].main)}
      ${createChildMessHtml(data[index], data[index].main.user_id)}
          <button class="add_btn btn icon" title="Add Comment" data-id="${data[index].main.id}"></button>
      </div>`;
      $(el).html(str);
    });
  };

  $.ajax({
    type: 'GET',
    url: messUrl,
    dataType: 'json',
    error: jqXHR => whenError(jqXHR),
    success: data => {
      replaceMessages(data);
      originTarget = null;
      originText = '';
      hiddenAlert();
    },
  });
};

const reRenderUser = () => {
  const createEditeSectionHtml = data => (
    data.id === 1 ? '' : `
    <div class="message__edite">
      <button class="edite_btn btn icon" title="edite" data-id=${data.id}/>
      <button title="delete" class="btn delete_btn icon" data-id=${data.id} data-type="user" />
    </div>
    `
  );

  const replaceUsers = data => {
    $('.users_table tbody tr').each((index, el) => {
      const str = `
      <th scope="row" align="center" class="user_table__td">${data[index].id}</th>
      <td class="user_table__td">${data[index].username}</td>
      <td class="user_table__td">${data[index].nickname}</td>
      <td class="user_table__td permission__th">${data[index].permission}</td>
      <td class="user_table__td">${createEditeSectionHtml(data[index])}</td>
      `;
      $(el).html(str);
    });
  };

  $.ajax({
    type: 'GET',
    url: userUrl,
    dataType: 'json',
    error: jqXHR => whenError(jqXHR),
    success: data => {
      replaceUsers(data);
      originTarget = null;
      originText = '';
      hiddenAlert();
    },
  });
};

const sendRequest = (method, target) => {
  let url = messUrl;
  if (method === 'DELETE') url = `${messUrl}?id=${$(target).data('id')}`;
  return $.ajax({
    type: method,
    url,
    dataType: 'json',
    data: $(target).closest('form').serialize(),
    error: jqXHR => whenError(jqXHR),
    success: () => {
      showAlert('Under processing', 'fewtime');
      reRenderMessages();
      return true;
    },
  });
};
const sendUserRequest = (method, target) => {
  const url = method === 'DELETE' ? `${userUrl}?id=${$(target).data('id')}` : userUrl;
  console.log('url', url);
  $.ajax({
    type: method,
    url,
    dataType: 'json',
    data: $(target).closest('form').serialize(),
    error: jqXHR => whenError(jqXHR),
    success: res => {
      showAlert('Under processing', 'fewtime');
      showAlert(res);
      reRenderUser();
    },
  });
};

// 後端根據是否拿到 parentId ID決定是增加子留言、編輯或新增主留言
const addChildMess = target => sendRequest('POST', target);
const updateMess = target => sendRequest('POST', target);
const addMainMess = target => {
  if (sendRequest('POST', target)) $('.comment_board_text').val('');
};
const deleteMess = target => sendRequest('DELETE', target);

const isEmpty = target => {
  let content = $(target).closest('form').find('textarea').val();
  while (content.charAt(0) === ' ') {
    content = content.substring(1);
  }
  if (content !== '') return false;
  alert('empty');
  return true;
};

if (messages) {
  messages.addEventListener('click',
    e => {
      if ($(e.target).hasClass('delete_btn')) {
        if (window.confirm('是否確定刪除 ?'))deleteMess(e.target);
      } else if ($(e.target).hasClass('add_sub_commit_btn')) {
        e.preventDefault();
        if (!isEmpty(e.target)) addChildMess(e.target);
      } else if ($(e.target).hasClass('edite__send_btn')) {
        e.preventDefault();
        if (!isEmpty(e.target)) updateMess(e.target);
      }
      changeEditeFrame('message', e);
    });
} else if (usersTable) {
  usersTable.addEventListener('click',
    e => {
      if ($(e.target).hasClass('delete_btn')) {
        if (window.confirm('是否確定刪除 ?')) sendUserRequest('DELETE', e.target);
      } else if ($(e.target).hasClass('edite__send_btn')) {
        e.preventDefault();
        sendUserRequest('POST', e.target);
      }
      changeEditeFrame('user', e);
    });
}


$('.comment_board_btn').click(e => {
  e.preventDefault();
  if (!isEmpty(e.target)) addMainMess(e.target);
});

const scrollFunction = () => {
  if ($(window).scrollTop() > 60) {
    $('#navbar').css('padding', '0.2rem 0.5rem');
    $('#logo').css('font-size', '1rem');
  } else {
    $('#navbar').css('padding', '0.5rem 1rem');
    $('#logo').css('font-size', '1.25rem');
  }
};

$(window).scroll(() => scrollFunction());
