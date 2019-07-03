import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { ChatDataService } from '../../shared/services/chat-data.service';

@Component({
  selector: 'app-chat-attachment',
  templateUrl: './chat-attachment.component.html',
  styleUrls: ['./chat-attachment.component.scss']
})
export class ChatAttachmentComponent implements OnInit {

  public assetUrl = Configs.assetBaseUrl;
  constructor(public chatDataService: ChatDataService) { }

  ngOnInit() {
  }

}
