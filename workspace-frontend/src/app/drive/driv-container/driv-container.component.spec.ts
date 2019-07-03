import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DrivContainerComponent } from './driv-container.component';

describe('DrivContainerComponent', () => {
  let component: DrivContainerComponent;
  let fixture: ComponentFixture<DrivContainerComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DrivContainerComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DrivContainerComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
