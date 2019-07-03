import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { ChatDataService } from '../../shared/services/chat-data.service';

@Component({
  selector: 'app-chat-single-user-chat',
  templateUrl: './chat-single-user-chat.component.html',
  styleUrls: ['./chat-single-user-chat.component.scss']
})
export class ChatSingleUserChatComponent implements OnInit {
  
  public assetUrl = Configs.assetBaseUrl;
  constructor(public chatDataService: ChatDataService) { }

  ngOnInit() {
  }

}
