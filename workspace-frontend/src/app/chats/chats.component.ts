import { Component, OnInit } from '@angular/core';
import { Configs } from '../config';
import { ChatDataService } from '../shared/services/chat-data.service';

@Component({
  selector: 'app-chats',
  templateUrl: './chats.component.html',
  styleUrls: ['./chats.component.scss']
})
export class ChatsComponent implements OnInit {

  public assetUrl = Configs.assetBaseUrl;
  constructor(public chatDataService: ChatDataService) { }

  ngOnInit() {
  }

}
