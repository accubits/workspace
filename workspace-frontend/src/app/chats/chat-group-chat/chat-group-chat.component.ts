import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { ChatDataService } from '../../shared/services/chat-data.service';
@Component({
  selector: 'app-chat-group-chat',
  templateUrl: './chat-group-chat.component.html',
  styleUrls: ['./chat-group-chat.component.scss']
})
export class ChatGroupChatComponent implements OnInit {

  public assetUrl = Configs.assetBaseUrl;
  constructor(public chatDataService: ChatDataService) { }

  ngOnInit() {
  }

}
