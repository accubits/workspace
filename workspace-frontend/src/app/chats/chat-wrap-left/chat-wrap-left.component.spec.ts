import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ChatWrapLeftComponent } from './chat-wrap-left.component';

describe('ChatWrapLeftComponent', () => {
  let component: ChatWrapLeftComponent;
  let fixture: ComponentFixture<ChatWrapLeftComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ChatWrapLeftComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ChatWrapLeftComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
