import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AsAnnouncementComponent } from './as-announcement.component';

describe('AsAnnouncementComponent', () => {
  let component: AsAnnouncementComponent;
  let fixture: ComponentFixture<AsAnnouncementComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AsAnnouncementComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AsAnnouncementComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
