import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WidgetCreationComponent } from './widget-creation.component';

describe('WidgetCreationComponent', () => {
  let component: WidgetCreationComponent;
  let fixture: ComponentFixture<WidgetCreationComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WidgetCreationComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WidgetCreationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
