import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EventEndComponent } from './event-end.component';

describe('EventEndComponent', () => {
  let component: EventEndComponent;
  let fixture: ComponentFixture<EventEndComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EventEndComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EventEndComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
