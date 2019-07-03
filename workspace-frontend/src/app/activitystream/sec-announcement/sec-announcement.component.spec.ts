import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SecAnnouncementComponent } from './sec-announcement.component';

describe('SecAnnouncementComponent', () => {
  let component: SecAnnouncementComponent;
  let fixture: ComponentFixture<SecAnnouncementComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SecAnnouncementComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SecAnnouncementComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
