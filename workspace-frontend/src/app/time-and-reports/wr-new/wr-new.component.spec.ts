import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WrNewComponent } from './wr-new.component';

describe('WrNewComponent', () => {
  let component: WrNewComponent;
  let fixture: ComponentFixture<WrNewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WrNewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WrNewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
