import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { GreetingWidgetComponent } from './greeting-widget.component';

describe('GreetingWidgetComponent', () => {
  let component: GreetingWidgetComponent;
  let fixture: ComponentFixture<GreetingWidgetComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ GreetingWidgetComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(GreetingWidgetComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
