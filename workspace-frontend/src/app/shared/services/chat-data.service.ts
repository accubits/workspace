import { Injectable } from '@angular/core';

@Injectable()

export class ChatDataService {

  constructor() { }

  chatModel = {

    inviteModal: {
      show: false
    },
    memberModal: {
      show: false
    },
    attachmentModal: {
      show: false
    },
    chatNew: {
      show: false
    },

  };

  inviteModal = { ...this.chatModel.inviteModal };
  memberModal = { ...this.chatModel.memberModal };
  attachmentModal = { ...this.chatModel.attachmentModal };
  chatNew = { ...this.chatModel.chatNew };

}
