import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AbRightComponent } from './ab-right.component';

describe('AbRightComponent', () => {
  let component: AbRightComponent;
  let fixture: ComponentFixture<AbRightComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AbRightComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AbRightComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
